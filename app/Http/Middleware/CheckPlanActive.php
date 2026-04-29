<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanActive
{
    /**
     * Cek apakah akun user sudah aktif.
     * User yang status-nya 'pending' (belum bayar/belum dikonfirmasi)
     * tidak boleh masuk dashboard.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // User dengan status pending → belum dikonfirmasi pembayarannya
        if ($user->status === 'pending') {
            // Cari payment terakhirnya buat redirect ke status
            $payment = $user->payments()->latest()->first();

            if ($payment) {
                return redirect()->route('payment.status', $user->id)
                    ->with('info', 'Akun kamu masih menunggu konfirmasi pembayaran.');
            }

            return redirect()->route('payment.show', $user->id)
                ->with('info', 'Selesaikan pembayaran untuk mengaktifkan akun kamu.');
        }

        // User suspended
        if ($user->status === 'suspended') {
            abort(403, 'Akun kamu telah disuspend. Hubungi admin untuk informasi lebih lanjut.');
        }

        return $next($request);
    }
}