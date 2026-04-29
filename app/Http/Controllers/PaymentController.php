<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Tampilkan halaman instruksi pembayaran
     * Route: GET /payment/{user}
     */
    public function show(User $user)
    {
        // Kalau user sudah aktif & plan cocok, langsung ke dashboard
        if ($user->status === 'active' && $user->plan !== 'starter') {
            return redirect()->route('dashboard');
        }

        // Cek apakah sudah ada payment pending
        $existingPayment = Payment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        $nominal = Payment::nominalByPlan($user->plan);

        return view('payment.show', compact('user', 'existingPayment', 'nominal'));
    }

    /**
     * Proses upload bukti transfer
     * Route: POST /payment/{user}/upload
     */
    public function upload(Request $request, User $user)
    {
        $request->validate([
            'metode' => ['required', 'in:qris,transfer'],
            'bukti'  => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'], // max 3MB
        ], [
            'bukti.required' => 'Bukti pembayaran wajib diupload.',
            'bukti.image'    => 'File harus berupa gambar (JPG, PNG).',
            'bukti.max'      => 'Ukuran file maksimal 3MB.',
        ]);

        // Cek sudah ada payment pending atau belum
        $existing = Payment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->withErrors(['bukti' => 'Kamu sudah mengupload bukti pembayaran. Mohon tunggu konfirmasi admin.']);
        }

        // Simpan file bukti
        $path = $request->file('bukti')->store('payments/bukti', 'public');

        // Buat record payment
        Payment::create([
            'user_id' => $user->id,
            'plan'    => $user->plan,
            'nominal' => Payment::nominalByPlan($user->plan),
            'metode'  => $request->metode,
            'bukti_path' => $path,
            'status'  => 'pending',
        ]);

        return redirect()->route('payment.status', $user->id)
            ->with('success', 'Bukti pembayaran berhasil dikirim! Kami akan mengkonfirmasi dalam 1x24 jam dan mengirimkan password ke email kamu.');
    }

    /**
     * Halaman status pembayaran (user bisa pantau progress)
     * Route: GET /payment/{user}/status
     */
    public function status(User $user)
    {
        $payment = Payment::where('user_id', $user->id)
            ->latest()
            ->first();

        return view('payment.status', compact('user', 'payment'));
    }
}