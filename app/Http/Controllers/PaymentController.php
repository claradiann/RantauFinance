<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    /**
     * Redirect user ke halaman pembayaran untuk upgrade plan
     * Route: GET /upgrade/{plan}
     */
    public function upgrade(string $plan)
    {
        if (!in_array($plan, ['personal', 'profesional'])) {
            return redirect()->back()->with('error', 'Plan tidak valid.');
        }

        Session::put('target_plan', $plan);
        return redirect()->route('payment.show');
    }

    /**
     * Tampilkan halaman instruksi pembayaran
     * Route: GET /payment
     */
    public function show()
    {
        // 1. Kasus User sudah login (Pending atau Upgrade)
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Tentukan plan target (jika upgrade, ambil dari session. Jika registrasi baru tapi sudah login, ambil dari user->plan)
            $targetPlan = Session::get('target_plan') ?: $user->plan;

            // Jika user active dan tidak sedang upgrade, arahkan ke dashboard
            if ($user->status === 'active' && !Session::has('target_plan')) {
                return redirect()->route('dashboard');
            }

            $existingPayment = $user->payments()->where('status', 'pending')->first();
            $nominal = Payment::nominalByPlan($targetPlan);

            // Buat objek semu untuk tampilan view agar plan yang muncul adalah plan target
            $userView = clone $user;
            $userView->plan = $targetPlan;

            return view('payment.show', [
                'user'            => $userView, // Kirim userView agar UI menampilkan plan target
                'existingPayment' => $existingPayment,
                'nominal'         => $nominal
            ]);
        }

        // 2. Kasus pendaftaran baru (Guest): Ambil data dari session
        $pending = Session::get('pending_registration');

        if (!$pending) {
            return redirect()->route('register')->with('error', 'Silakan isi data pendaftaran terlebih dahulu.');
        }

        $user = (object) $pending;
        $existingPayment = null;
        $nominal = Payment::nominalByPlan($user->plan);

        return view('payment.show', compact('user', 'existingPayment', 'nominal'));
    }

    /**
     * Proses upload bukti transfer
     * Route: POST /payment/upload
     */
    public function upload(Request $request)
    {
        // 1. Tentukan konteks: Login (Pending/Upgrade) atau Pendaftaran Baru (session)
        if (Auth::check()) {
            $user = Auth::user();
            $plan = Session::get('target_plan') ?: $user->plan;
            $pending = null;
        } else {
            $pending = Session::get('pending_registration');
            if (!$pending) {
                return redirect()->route('register')->with('error', 'Sesi pendaftaran berakhir. Silakan daftar ulang.');
            }
            $user = null;
            $plan = $pending['plan'];
        }

        $request->validate([
            'metode' => ['required', 'in:bca,mandiri,gopay,qris,transfer'],
            'bukti'  => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        return DB::transaction(function () use ($request, $pending, $user, $plan) {
            // Jika pendaftaran baru, buat user-nya dulu
            if (!$user) {
                /** @var array $pending */
                if (User::where('email', $pending['email'])->exists()) {
                    Session::forget('pending_registration');
                    return redirect()->route('register')->with('error', 'Email sudah terdaftar. Silakan login.');
                }

                $user = User::create([
                    'name'     => $pending['name'],
                    'email'    => $pending['email'],
                    'password' => $pending['password'],
                    'plan'     => 'starter', // Mulai sebagai starter
                    'status'   => 'active',  // Langsung aktif
                ]);

                Session::forget('pending_registration');
                Auth::login($user);
            }

            // Simpan file bukti
            $path = $request->file('bukti')->store('payments/bukti', 'public');

            // Hapus payment pending sebelumnya agar tidak menumpuk
            $user->payments()->where('status', 'pending')->delete();

            // Buat record payment baru
            Payment::create([
                'user_id'    => $user->id,
                'plan'       => $plan,
                'nominal'    => Payment::nominalByPlan($plan),
                'metode'     => $request->metode,
                'bukti_path' => $path,
                'status'     => 'pending',
            ]);

            // Bersihkan session target_plan jika ada
            Session::forget('target_plan');

            return redirect()->to(URL::signedRoute('payment.status', ['user' => $user->id]))
                ->with('success', 'Bukti pembayaran berhasil dikirim! Admin akan memverifikasi pembayaran kamu.');
        });
    }

    /**
     * Halaman status pembayaran (user bisa pantau progress)
     * Route: GET /payment/{user}/status
     */
    public function status(User $user)
    {
        // Pastikan user hanya bisa akses datanya sendiri
        if (Auth::id() !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $payment = Payment::where('user_id', $user->id)
            ->latest()
            ->first();

        return view('payment.status', compact('user', 'payment'));
    }
}