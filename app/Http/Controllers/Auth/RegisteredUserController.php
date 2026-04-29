<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan form registrasi
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi user baru
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'plan'     => ['required', 'in:starter,personal,profesional'],
        ]);

        $plan = $request->plan;

        // Starter langsung aktif, paket berbayar butuh payment dulu
        $status = $plan === 'starter' ? 'active' : 'pending';

        // Untuk starter, password dari form sendiri
        // Untuk berbayar, password akan dikirim via email setelah konfirmasi payment
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'plan'     => $plan,
            'status'   => $status,
        ]);

        event(new Registered($user));

        // Kalau starter → langsung login ke dashboard
        if ($plan === 'starter') {
            Auth::login($user);
            return redirect()->route('dashboard');
        }

        // Kalau personal/profesional → ke halaman payment (belum login dulu)
        return redirect()->route('payment.show', ['user' => $user->id])
            ->with('info', 'Akun berhasil dibuat! Selesaikan pembayaran untuk mengaktifkan paket ' . ucfirst($plan) . '.');
    }
}