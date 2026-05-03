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
        $bulan  = $request->bulan ?? Carbon::now()->month;
        $tahun  = $request->tahun ?? Carbon::now()->year;

        $pemasukan           = $finance->pemasukanBulanIni($userId, $bulan, $tahun);
        $pengeluaran         = $finance->pengeluaranBulanIni($userId, $bulan, $tahun);
        $selisih             = $pemasukan - $pengeluaran;
        $pemasukanKategori   = $finance->pemasukanPerKategori($userId, $bulan, $tahun);
        $pengeluaranKategori = $finance->pengeluaranPerKategori($userId, $bulan, $tahun);
        $transaksi           = $finance->transaksiPerBulan($userId, $bulan, $tahun);
        $laporanTahunan      = $finance->laporanTahunan($userId, $tahun);

        return view('laporan.index', compact(
            'pemasukan', 'pengeluaran', 'selisih',
            'pemasukanKategori', 'pengeluaranKategori',
            'transaksi', 'laporanTahunan',
            'bulan', 'tahun'
        ));
    }
}