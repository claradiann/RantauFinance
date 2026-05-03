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
        $profesionalOnly = ['laporan_tahunan', 'analisis_per_kategori', 'kategori_custom', 'notif_email', 'notif_telegram'];

        if (in_array($feature, $profesionalOnly)) {
            return 'profesional';
        }

        return $currentPlan === 'starter' ? 'personal' : 'profesional';
    }
}