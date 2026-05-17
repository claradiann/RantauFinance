<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminController extends Controller
{
    // Dashboard admin
    public function index()
    {
        $stats = [
            'pending'         => Payment::where('status', 'pending')->count(),
            'confirmed_today' => Payment::where('status', 'confirmed')
                ->whereDate('confirmed_at', today())->count(),
            'total_users'     => User::where('is_admin', false)->count(),
            'active_paid'     => User::whereIn('plan', ['personal', 'profesional'])
                ->where('status', 'active')->count(),
            'revenue_month'   => Payment::where('status', 'confirmed')
                ->whereMonth('confirmed_at', now()->month)
                ->whereYear('confirmed_at', now()->year)
                ->sum('nominal'),
        ];

        $pendingPayments = Payment::with('user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        $recentConfirmed = Payment::with(['user', 'confirmedBy'])
            ->where('status', 'confirmed')
            ->latest('confirmed_at')
            ->limit(5)
            ->get();

        return view('admin.index', compact('stats', 'pendingPayments', 'recentConfirmed'));
    }

    // Detail pembayaran
    public function paymentDetail(Payment $payment)
    {
        $payment->load('user');
        return view('admin.payment-detail', compact('payment'));
    }

    // Konfirmasi pembayaran → aktifkan akun + kirim email
    public function confirm(Payment $payment)
    {
        if (! $payment->isPending()) {
            return back()->with('error', 'Pembayaran ini sudah diproses sebelumnya.');
        }

        $user = $payment->user;

        $user->update([
            'status'          => 'active',
            'plan'            => $payment->plan,
            'plan_expires_at' => Carbon::now()->addMonth(),
        ]);

        $payment->update([
            'status'       => 'confirmed',
            'confirmed_by' => Auth::id(),
            'confirmed_at' => now(),
        ]);

        try {
            Mail::send('email.payment-confirmed', [
                'user'          => $user,
                'payment'       => $payment,
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject('🎉 Akun RantauFinance Kamu Sudah Aktif!');
            });
        } catch (\Exception $e) {
            Log::error('Gagal kirim email: ' . $e->getMessage());
            return redirect()->route('admin.index')
                ->with('warning', "Akun dikonfirmasi, tapi email gagal terkirim.");
        }

        return redirect()->route('admin.index')
            ->with('success', "Pembayaran #{$payment->id} ({$user->name}) dikonfirmasi. Akun sekarang aktif dengan plan " . ucfirst($payment->plan) . ".");
    }

    // Tolak pembayaran
    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'catatan_admin' => ['nullable', 'string', 'max:500'],
        ]);

        if (! $payment->isPending()) {
            return back()->with('error', 'Pembayaran ini sudah diproses sebelumnya.');
        }

        $catatan = $request->catatan_admin ?? 'Bukti pembayaran tidak valid atau nominal tidak sesuai.';

        $payment->update([
            'status'        => 'rejected',
            'catatan_admin' => $catatan,
            'confirmed_by'  => Auth::id(),
            'confirmed_at'  => now(),
        ]);

        $user = $payment->user;
        if ($user->status === 'pending') {
            $user->update(['status' => 'active', 'plan' => 'starter']);
        }

        try {
            Mail::send('email.payment-rejected', [
                'user'    => $user,
                'payment' => $payment,
                'catatan' => $catatan,
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject('❌ Pembayaran RantauFinance Tidak Dapat Diproses');
            });
        } catch (\Exception $e) {
            Log::error('Gagal kirim email tolak: ' . $e->getMessage());
        }

        return redirect()->route('admin.index')
            ->with('success', "Pembayaran #{$payment->id} ditolak. Email notifikasi dikirim ke {$user->email}.");
    }

    // Daftar semua user
    public function users(Request $request)
    {
        $query = User::where('is_admin', false)->latest();

        if ($request->plan)   $query->where('plan', $request->plan);
        if ($request->status) $query->where('status', $request->status);
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(20)->withQueryString();
        return view('admin.users', compact('users'));
    }

    // Detail user
    public function userDetail(User $user)
    {
        $payments = Payment::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('admin.user-detail', compact('user', 'payments'));
    }

    // Riwayat semua payment
    public function payments(Request $request)
    {
        $query = Payment::with('user')->latest();

        if ($request->status) $query->where('status', $request->status);
        if ($request->plan)   $query->where('plan', $request->plan);
        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $payments = $query->paginate(20)->withQueryString();

        $stats = [
            'pending'   => Payment::where('status', 'pending')->count(),
            'confirmed' => Payment::where('status', 'confirmed')->count(),
            'rejected'  => Payment::where('status', 'rejected')->count(),
            'total'     => Payment::count(),
        ];

        return view('admin.payments', compact('payments', 'stats'));
    }

    // Suspend user
    public function suspend(User $user)
    {
        if ($user->is_admin) return back()->with('error', 'Tidak bisa suspend akun admin.');
        $user->update(['status' => 'suspended']);
        return back()->with('success', "Akun {$user->name} berhasil disuspend.");
    }

    // Unsuspend user
    public function unsuspend(User $user)
    {
        $user->update(['status' => 'active']);
        return back()->with('success', "Akun {$user->name} berhasil diaktifkan kembali.");
    }

    // Ubah plan user secara manual
    public function changePlan(Request $request, User $user)
    {
        $request->validate([
            'plan'            => ['required', 'in:starter,personal,profesional'],
            'plan_expires_at' => ['nullable', 'date', 'after:today'],
        ]);

        if ($user->is_admin) {
            return back()->with('error', 'Tidak bisa mengubah plan akun admin.');
        }

        $user->update([
            'plan'            => $request->plan,
            'plan_expires_at' => $request->plan === 'starter' ? null : $request->plan_expires_at,
            'status'          => 'active',
        ]);

        return back()->with('success', "Plan {$user->name} berhasil diubah ke " . ucfirst($request->plan) . ".");
    }

    // Reset password user — menggunakan template email yang benar
    public function resetPassword(User $user)
    {
        $plainPassword = strtoupper(Str::random(4)) . rand(100, 999) . Str::random(1);
        $user->update(['password' => Hash::make($plainPassword)]);

        try {
            Mail::send('email.password-reset', [
                'user'          => $user,
                'plainPassword' => $plainPassword,
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject('🔑 Reset Password RantauFinance');
            });
        } catch (\Exception $e) {
            Log::error('Gagal kirim email reset password: ' . $e->getMessage());
            return back()->with('warning', "Password baru: <strong>{$plainPassword}</strong> — email gagal terkirim, sampaikan manual ke user.");
        }

        return back()->with('success', "Password {$user->name} direset. Dikirim ke {$user->email}.");
    }

    // Laporan Revenue
    public function revenue()
    {
        // Revenue bulanan 12 bulan terakhir
        $monthlyRevenue = Payment::where('status', 'confirmed')
            ->where('confirmed_at', '>=', now()->subMonths(11)->startOfMonth())
            ->selectRaw("DATE_FORMAT(confirmed_at, '%Y-%m') as bulan, SUM(nominal) as total, COUNT(*) as jumlah")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Isi bulan kosong agar chart lengkap 12 bulan
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $found = $monthlyRevenue->firstWhere('bulan', $key);
            $months->push([
                'bulan'  => now()->subMonths($i)->translatedFormat('M Y'),
                'key'    => $key,
                'total'  => $found ? (float) $found->total : 0,
                'jumlah' => $found ? (int) $found->jumlah : 0,
            ]);
        }

        // Revenue per plan
        $revenueByPlan = Payment::where('status', 'confirmed')
            ->selectRaw("plan, SUM(nominal) as total, COUNT(*) as jumlah")
            ->groupBy('plan')
            ->get()
            ->keyBy('plan');

        // Statistik keseluruhan
        $totalRevenue    = Payment::where('status', 'confirmed')->sum('nominal');
        $totalConfirmed  = Payment::where('status', 'confirmed')->count();
        $avgPerPayment   = $totalConfirmed > 0 ? $totalRevenue / $totalConfirmed : 0;

        // Revenue bulan ini vs bulan lalu
        $thisMonth = Payment::where('status', 'confirmed')
            ->whereMonth('confirmed_at', now()->month)
            ->whereYear('confirmed_at', now()->year)
            ->sum('nominal');

        $lastMonth = Payment::where('status', 'confirmed')
            ->whereMonth('confirmed_at', now()->subMonth()->month)
            ->whereYear('confirmed_at', now()->subMonth()->year)
            ->sum('nominal');

        $growth = $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) : ($thisMonth > 0 ? 100 : 0);

        // Top 5 user by total spending
        $topUsers = User::where('is_admin', false)
            ->withSum(['payments as total_bayar' => fn($q) => $q->where('status', 'confirmed')], 'nominal')
            ->having('total_bayar', '>', 0)
            ->orderByDesc('total_bayar')
            ->limit(5)
            ->get();

        return view('admin.revenue', compact(
            'months', 'revenueByPlan', 'totalRevenue', 'totalConfirmed',
            'avgPerPayment', 'thisMonth', 'lastMonth', 'growth', 'topUsers'
        ));
    }
}