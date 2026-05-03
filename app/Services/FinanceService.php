<?php

namespace App\Services;

use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceService
{
    // SALDO
    public function saldo($userId)
    {
        $pemasukan = $this->totalPemasukan($userId);
        $pengeluaran = $this->totalPengeluaran($userId);

        return $pemasukan - $pengeluaran;
    }

    public function totalPemasukan($userId)
    {
        return Transaksi::where('user_id', $userId)
            ->whereHas('kategori', fn($q) => $q->where('tipe', 'pemasukan'))
            ->sum('jumlah');
    }

    public function totalPengeluaran($userId)
    {
        return Transaksi::where('user_id', $userId)
            ->whereHas('kategori', fn($q) => $q->where('tipe', 'pengeluaran'))
            ->sum('jumlah');
    }

    // BULAN INI
    public function pemasukanBulanIni($userId, $month, $year)
    {
        return $this->sumByType($userId, 'pemasukan', $month, $year);
    }

    public function pengeluaranBulanIni($userId, $month, $year)
    {
        return $this->sumByType($userId, 'pengeluaran', $month, $year);
    }

    private function sumByType($userId, $type, $month, $year)
    {
        return Transaksi::where('user_id', $userId)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->whereHas('kategori', fn($q) => $q->where('tipe', $type))
            ->sum('jumlah');
    }

    // TRANSAKSI TERBARU
    public function transaksiTerbaru($userId)
    {
        return Transaksi::with('kategori')
            ->where('user_id', $userId)
            ->latest('tanggal')
            ->take(5)
            ->get();
    }

    // PER KATEGORI
    public function pengeluaranPerKategori($userId, $month, $year)
    {
        return Transaksi::where('transaksi.user_id', $userId)
            ->whereMonth('transaksi.tanggal', $month)
            ->whereYear('transaksi.tanggal', $year)
            ->join('kategori', 'transaksi.kategori_id', '=', 'kategori.id')
            ->where('kategori.tipe', 'pengeluaran')
            ->select('kategori.nama', DB::raw('SUM(transaksi.jumlah) as total'))
            ->groupBy('kategori.nama')
            ->orderByDesc('total')
            ->get();
    }

    // CHART 6 BULAN
    public function chart6Bulan($userId)
    {
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);

            $data[] = [
                'label' => $date->translatedFormat('M'),
                'pemasukan' => $this->sumByType($userId, 'pemasukan', $date->month, $date->year),
                'pengeluaran' => $this->sumByType($userId, 'pengeluaran', $date->month, $date->year),
            ];
        }

        return $data;
    }

    // TOTAL TRANSAKSI
    public function totalTransaksiBulanIni($userId, $month, $year)
    {
        return Transaksi::where('user_id', $userId)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->count();
    }

    // ----- LAPORAN PER BULAN (ringkasan 12 bulan dalam setahun) -----
    public function laporanTahunan($userId, $year)
    {
        $data = [];

        for ($m = 1; $m <= 12; $m++) {
            $pemasukan   = $this->sumByType($userId, 'pemasukan', $m, $year);
            $pengeluaran = $this->sumByType($userId, 'pengeluaran', $m, $year);

            $data[] = [
                'bulan'       => $m,
                'label'       => Carbon::create($year, $m, 1)->translatedFormat('F'),
                'pemasukan'   => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'selisih'     => $pemasukan - $pengeluaran,
            ];
        }

        return $data;
    }

    // PEMASUKAN PER KATEGORI
    public function pemasukanPerKategori($userId, $month, $year)
    {
        return Transaksi::where('transaksi.user_id', $userId)
        ->whereMonth('transaksi.tanggal', $month)
        ->whereYear('transaksi.tanggal', $year)
        ->join('kategori', 'transaksi.kategori_id', '=', 'kategori.id')
        ->where('kategori.tipe', 'pemasukan')
        ->select('kategori.nama', DB::raw('SUM(transaksi.jumlah) as total'))
        ->groupBy('kategori.nama')
        ->orderByDesc('total')
        ->get();
    }

    // SEMUA TRANSAKSI BULAN INI (untuk tabel detail)
    public function transaksiPerBulan($userId, $month, $year)
    {
        return Transaksi::with('kategori')
        ->where('user_id', $userId)
        ->whereMonth('tanggal', $month)
        ->whereYear('tanggal', $year)
        ->orderBy('tanggal', 'desc')
        ->get();
    }
}