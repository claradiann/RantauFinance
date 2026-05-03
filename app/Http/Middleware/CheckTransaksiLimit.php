<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTransaksiLimit
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->isStarter()) {
            return $next($request);
        }

        $maxPerBulan    = $user->maxTransaksiPerBulan(); // 30
        $jumlahBulanIni = $user->transaksi()
            ->whereYear('tanggal', now()->year)
            ->whereMonth('tanggal', now()->month)
            ->count();

        if ($jumlahBulanIni >= $maxPerBulan) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Batas transaksi bulanan paket Starter sudah tercapai (30/bulan).',
                    'limit'   => $maxPerBulan,
                    'current' => $jumlahBulanIni,
                ], 403);
            }

            return redirect()->route('transaksi.create')
                ->with('limit_reached', [
                    'limit'   => $maxPerBulan,
                    'current' => $jumlahBulanIni,
                    'message' => 'Kamu sudah mencapai batas ' . $maxPerBulan . ' transaksi bulan ini. Upgrade ke Personal untuk unlimited.',
                ]);
        }

        return $next($request);
    }
}