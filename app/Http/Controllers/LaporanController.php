<?php

namespace App\Http\Controllers;

use App\Services\FinanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request, FinanceService $finance)
    {
        $userId = Auth::id();
        $bulan  = (int) ($request->bulan ?? Carbon::now()->month);
        $tahun  = (int) ($request->tahun ?? Carbon::now()->year);

        $pemasukan           = $finance->pemasukanBulanIni($userId, $bulan, $tahun);
        $pengeluaran         = $finance->pengeluaranBulanIni($userId, $bulan, $tahun);
        $selisih             = $pemasukan - $pengeluaran;
        $pemasukanKategori   = $finance->pemasukanPerKategori($userId, $bulan, $tahun);
        $pengeluaranKategori = $finance->pengeluaranPerKategori($userId, $bulan, $tahun);
        $transaksi           = $finance->transaksiPerBulan($userId, $bulan, $tahun);
        $laporanTahunan      = $finance->laporanTahunan($userId, $tahun);

        $smartInsight = null;
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->effectivePlan() === 'profesional') {
            $insightMessages = [];
            if ($pengeluaran > $pemasukan) {
                $insightMessages[] = "⚠️ Pengeluaran di periode ini melebihi pemasukan (Defisit Rp " . number_format($pengeluaran - $pemasukan, 0, ',', '.') . "). Pertimbangkan untuk mengevaluasi budget.";
            } elseif ($pemasukan > 0 && ($pengeluaran / $pemasukan) > 0.8) {
                $insightMessages[] = "⚠️ Pengeluaran mencapai " . round(($pengeluaran/$pemasukan)*100) . "% dari pemasukan di periode ini. Tetap waspada!";
            } else {
                $insightMessages[] = "🌟 Kondisi keuangan pada periode ini terpantau sangat sehat dengan surplus Rp " . number_format($pemasukan - $pengeluaran, 0, ',', '.') . ".";
            }
            
            if ($pengeluaranKategori->count() > 0) {
                $topKategori = $pengeluaranKategori->first();
                $insightMessages[] = "💡 Kategori penyedot dana terbesar adalah <strong>{$topKategori->nama}</strong> sejumlah Rp " . number_format($topKategori->total, 0, ',', '.') . ".";
            }
            $smartInsight = $insightMessages;
        }

        return view('laporan.index', compact(
            'pemasukan', 'pengeluaran', 'selisih',
            'pemasukanKategori', 'pengeluaranKategori',
            'transaksi', 'laporanTahunan',
            'bulan', 'tahun', 'smartInsight'
        ));
    }
}