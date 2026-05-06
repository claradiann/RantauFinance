<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeature
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->canAccess($feature)) {
            $upgradeTo = $this->suggestUpgrade($user->effectivePlan(), $feature);

            if ($request->expectsJson()) {
                return response()->json([
                    'message'    => 'Fitur ini tidak tersedia di paket ' . $user->planLabel() . '.',
                    'upgrade_to' => $upgradeTo,
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('upgrade_required', [
                    'feature'    => $feature,
                    'current'    => $user->planLabel(),
                    'upgrade_to' => $upgradeTo,
                    'message'    => 'Fitur ini membutuhkan paket ' . ucfirst($upgradeTo) . ' atau lebih tinggi.',
                ]);
        }

        return $next($request);
    }

    private function suggestUpgrade(string $currentPlan, string $feature): string
    {
        $profesionalOnly = [
            'insight_otomatis', 'analisis_kebiasaan', 'perbandingan_bulanan',
            'notifikasi_pintar', 'peringatan_budget', 'export_csv_pdf',
            'analisis_kategori_detail', 'kategori_custom_unlimited',
        ];

        if (in_array($feature, $profesionalOnly)) {
            return 'profesional';
        }

        return $currentPlan === 'starter' ? 'personal' : 'profesional';
    }
}