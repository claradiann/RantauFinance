<?php

namespace App\Http\Controllers;

use App\Services\FinanceService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(FinanceService $finance)
    {
        if (Auth::user()->is_admin) {
            return redirect()->route('admin.index');
        }
        
        $userId = Auth::id();
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        // Dari FinanceService
        $saldo = $finance->saldo($userId);
        $pemasukanBulanIni = $finance->pemasukanBulanIni($userId, $currentMonth, $currentYear);
        $pengeluaranBulanIni = $finance->pengeluaranBulanIni($userId, $currentMonth, $currentYear);
        $transaksiTerbaru = $finance->transaksiTerbaru($userId);
        $pengeluaranPerKategori = $finance->pengeluaranPerKategori($userId, $currentMonth, $currentYear);
        $chartData = $finance->chart6Bulan($userId);
        return view('transaksi.dashboard', compact(
            'saldo',
            'pemasukanBulanIni',
            'pengeluaranBulanIni',
            'transaksiTerbaru',
            'pengeluaranPerKategori',
            'chartData',
            'totalTransaksi',
            'currentMonth',
            'currentYear'
        ));
    }
}