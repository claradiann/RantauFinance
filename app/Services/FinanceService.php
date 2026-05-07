<?php

namespace App\Services;

use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceService
{
    // SALDO
    public function saldo(int $userId)
    {
        $pemasukan = $this->totalPemasukan($userId);
        $pengeluaran = $this->totalPengeluaran($userId);

        return $pemasukan - $pengeluaran;
    }

    public function totalPemasukan(int $userId)
    {
        return Transaksi::where('user_id', $userId)
            ->whereHas('kategori', fn($q) => $q->where('tipe', 'pemasukan'))
            ->sum('jumlah');
    }

    public function totalPengeluaran(int $userId)
    {
        return Transaksi::where('user_id', $userId)
            ->whereHas('kategori', fn($q) => $q->where('tipe', 'pengeluaran'))
            ->sum('jumlah');
    }

    // BULAN INI
    public function pemasukanBulanIni(int $userId, int $month, int $year)
    {
        return $this->sumByType($userId, 'pemasukan', $month, $year);
    }

    public function pengeluaranBulanIni(int $userId, int $month, int $year)
    {
        return $this->sumByType($userId, 'pengeluaran', $month, $year);
    }

    private function sumByType(int $userId, string $type, int $month, int $year)
    {
        return Transaksi::where('user_id', $userId)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->whereHas('kategori', fn($q) => $q->where('tipe', $type))
            ->sum('jumlah');
    }

    // TRANSAKSI TERBARU
    public function transaksiTerbaru(int $userId)
    {
        return Transaksi::with('kategori')
            ->where('user_id', $userId)
            ->latest('tanggal')
            ->take(5)
            ->get();
    }

    // PER KATEGORI
    public function pengeluaranPerKategori(int $userId, int $month, int $year)
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

    // CHART 6 BULAN (Optimized: 1 Query)
    public function chart6Bulan(int $userId)
    {
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();

        $stats = Transaksi::where('transaksi.user_id', $userId)
            ->where('tanggal', '>=', $startDate)
            ->join('kategori', 'transaksi.kategori_id', '=', 'kategori.id')
            ->select(
                DB::raw('MONTH(tanggal) as month'),
                DB::raw('YEAR(tanggal) as year'),
                'kategori.tipe',
                DB::raw('SUM(jumlah) as total')
            )
            ->groupBy('year', 'month', 'kategori.tipe')
            ->get();

        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $m = $date->month;
            $y = $date->year;

            $pemasukan = $stats->where('month', $m)->where('year', $y)->where('tipe', 'pemasukan')->first()->total ?? 0;
            $pengeluaran = $stats->where('month', $m)->where('year', $y)->where('tipe', 'pengeluaran')->first()->total ?? 0;

            $data[] = [
                'label' => $date->translatedFormat('M'),
                'pemasukan' => (float) $pemasukan,
                'pengeluaran' => (float) $pengeluaran,
            ];
        }

        return $data;
    }

    // TOTAL TRANSAKSI
    public function totalTransaksiBulanIni(int $userId, int $month, int $year)
    {
        return Transaksi::where('user_id', $userId)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->count();
    }

    // ----- LAPORAN PER BULAN (Optimized: 1 Query) -----
    public function laporanTahunan(int $userId, int $year)
    {
        $stats = Transaksi::where('transaksi.user_id', $userId)
            ->whereYear('tanggal', $year)
            ->join('kategori', 'transaksi.kategori_id', '=', 'kategori.id')
            ->select(
                DB::raw('MONTH(tanggal) as month'),
                'kategori.tipe',
                DB::raw('SUM(jumlah) as total')
            )
            ->groupBy('month', 'kategori.tipe')
            ->get();

        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $pemasukan   = $stats->where('month', $m)->where('tipe', 'pemasukan')->first()->total ?? 0;
            $pengeluaran = $stats->where('month', $m)->where('tipe', 'pengeluaran')->first()->total ?? 0;

            $data[] = [
                'bulan'       => $m,
                'label'       => Carbon::create($year, $m, 1)->translatedFormat('F'),
                'pemasukan'   => (float) $pemasukan,
                'pengeluaran' => (float) $pengeluaran,
                'selisih'     => (float) $pemasukan - $pengeluaran,
            ];
        }

        return $data;
    }

    // PEMASUKAN PER KATEGORI
    public function pemasukanPerKategori(int $userId, int $month, int $year)
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
    public function transaksiPerBulan(int $userId, int $month, int $year)
    {
        return Transaksi::with('kategori')
        ->where('user_id', $userId)
        ->whereMonth('tanggal', $month)
        ->whereYear('tanggal', $year)
        ->orderBy('tanggal', 'desc')
        ->get();
    }

    // BUDGET WARNINGS (Hanya untuk Personal & Profesional)
    public function saldoWarnings(int $userId)
    {
        $warnings = [];

        $totalPemasukan = $this->totalPemasukan($userId);
        $totalPengeluaran = $this->totalPengeluaran($userId);

        if ($totalPemasukan <= 0) return $warnings;

        $persen = ($totalPengeluaran / $totalPemasukan) * 100;

        if ($persen >= 100) {
            $warnings[] = [
                'id'      => 'saldo-habis',
                'type'    => 'danger',
                'icon'    => '🚨',
                'title'   => 'Saldo Habis!',
                'message' => 'Total pengeluaran kamu sudah melebihi total pemasukan.',
                'time'    => 'Sekarang'
            ];
        } elseif ($persen >= 80) {
            $warnings[] = [
                'id'      => 'saldo-menipis',
                'type'    => 'warning',
                'icon'    => '⚠️',
                'title'   => 'Saldo Menipis',
                'message' => 'Total pengeluaran sudah mencapai ' . round($persen) . '% dari total pemasukan.',
                'time'    => 'Sekarang'
            ];
        }

        return $warnings;
    }

    public function allWarnings(int $userId)
    {
        return array_merge(
            $this->saldoWarnings($userId),
            $this->budgetWarnings($userId)
        );
    }

    public function budgetWarnings(int $userId)
    {
        $now   = Carbon::now();
        $month = $now->month;
        $year  = $now->year;

        $budgets = \App\Models\Budget::with('kategori')
            ->where('user_id', $userId)
            ->where('bulan', $month)
            ->where('tahun', $year)
            ->get();

        $warnings = [];

        foreach ($budgets as $b) {
            $terpakai = Transaksi::where('user_id', $userId)
                ->where('kategori_id', $b->kategori_id)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->sum('jumlah');

            $persen = $b->jumlah > 0 ? ($terpakai / $b->jumlah) * 100 : 0;

            if ($persen >= 100) {
                $warnings[] = [
                    'id'      => 'budget-over-' . $b->id,
                    'type'    => 'danger',
                    'icon'    => '🚨',
                    'title'   => 'Budget Terlampaui!',
                    'message' => "Pengeluaran untuk <strong>{$b->kategori->nama}</strong> telah melebihi budget bulanan.",
                    'time'    => 'Sekarang'
                ];
            } elseif ($persen >= 80) {
                $warnings[] = [
                    'id'      => 'budget-warn-' . $b->id,
                    'type'    => 'warning',
                    'icon'    => '⚠️',
                    'title'   => 'Budget Menipis',
                    'message' => "Pengeluaran <strong>{$b->kategori->nama}</strong> sudah mencapai " . round($persen) . "% dari budget.",
                    'time'    => 'Sekarang'
                ];
            }
        }

        return $warnings;
    }
}