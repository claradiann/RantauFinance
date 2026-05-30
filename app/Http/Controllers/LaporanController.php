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
        if ($user->canAccess('insight_otomatis')) {
            $insightMessages = [];

            // 1. Kondisi Keuangan & Surplus/Defisit
            if ($pengeluaran > $pemasukan) {
                $insightMessages[] = "⚠️ <strong>Defisit Keuangan:</strong> Pengeluaran Anda melebihi pemasukan dengan selisih <strong>Rp " . number_format($pengeluaran - $pemasukan, 0, ',', '.') . "</strong>. Disarankan untuk menekan pengeluaran non-esensial dan meninjau kembali anggaran belanja Anda.";
            } elseif ($pemasukan > 0) {
                $burnRate = ($pengeluaran / $pemasukan) * 100;
                if ($burnRate > 80) {
                    $insightMessages[] = "⚠️ <strong>Waspada Pengeluaran:</strong> Anda telah membelanjakan <strong>" . round($burnRate) . "%</strong> dari total pemasukan di periode ini. Sisa saldo Anda menipis, pertimbangkan untuk membatasi belanja tambahan.";
                } else {
                    $insightMessages[] = "🌟 <strong>Kondisi Keuangan Sehat:</strong> Anda mencatatkan surplus sebesar <strong>Rp " . number_format($pemasukan - $pengeluaran, 0, ',', '.') . "</strong> periode ini. Pertahankan pengelolaan yang baik ini!";
                }
            } elseif ($pengeluaran > 0) {
                $insightMessages[] = "⚠️ <strong>Tanpa Pemasukan:</strong> Belum ada pemasukan yang tercatat untuk periode ini, namun terdapat pengeluaran sebesar <strong>Rp " . number_format($pengeluaran, 0, ',', '.') . "</strong>. Pastikan Anda menggunakan dana darurat dengan bijak.";
            } else {
                $insightMessages[] = "ℹ️ <strong>Belum Ada Transaksi:</strong> Belum ada data keuangan untuk periode ini. Mulai mencatat transaksi Anda untuk melihat analisis otomatis di sini.";
            }

            // 2. Rasio Menabung (Savings Rate)
            if ($pemasukan > 0) {
                $savingsRate = (($pemasukan - $pengeluaran) / $pemasukan) * 100;
                if ($savingsRate >= 20) {
                    $insightMessages[] = "💰 <strong>Rasio Menabung Baik:</strong> Anda berhasil menabung <strong>" . round($savingsRate) . "%</strong> dari total pemasukan. Ini memenuhi atau melampaui aturan ideal 20% dalam pengelolaan keuangan.";
                } elseif ($savingsRate > 0) {
                    $insightMessages[] = "💡 <strong>Rasio Menabung Rendah:</strong> Rasio menabung Anda adalah <strong>" . round($savingsRate) . "%</strong> periode ini. Cobalah targetkan minimal 20% dengan mengalokasikan tabungan di awal bulan.";
                } else {
                    $insightMessages[] = "🚨 <strong>Tidak Ada Tabungan:</strong> Rasio menabung Anda negatif (<strong>" . round($savingsRate) . "%</strong>). Disarankan untuk segera menganalisis pos pengeluaran terbesar Anda.";
                }
            }

            // 3. Perbandingan Bulanan (Monthly Trend)
            $prevMonth = $bulan - 1;
            $prevYear = $tahun;
            if ($prevMonth === 0) {
                $prevMonth = 12;
                $prevYear = $tahun - 1;
            }
            $pengeluaranPrev = $finance->pengeluaranBulanIni($userId, $prevMonth, $prevYear);
            if ($pengeluaranPrev > 0) {
                $diffPercent = (($pengeluaran - $pengeluaranPrev) / $pengeluaranPrev) * 100;
                if ($diffPercent > 10) {
                    $insightMessages[] = "📈 <strong>Pengeluaran Meningkat:</strong> Total pengeluaran Anda naik sebesar <strong>" . round($diffPercent) . "%</strong> dibandingkan bulan lalu (Rp " . number_format($pengeluaran, 0, ',', '.') . " vs Rp " . number_format($pengeluaranPrev, 0, ',', '.') . "). Periksa detail transaksi untuk melihat pos pengeluaran yang membesar.";
                } elseif ($diffPercent < -10) {
                    $insightMessages[] = "📉 <strong>Pengeluaran Menurun:</strong> Luar biasa! Pengeluaran Anda berkurang sebesar <strong>" . round(abs($diffPercent)) . "%</strong> dibandingkan bulan lalu. Langkah hemat ini sangat membantu akumulasi aset tabungan Anda.";
                } else {
                    $insightMessages[] = "🔄 <strong>Pengeluaran Stabil:</strong> Pengeluaran Anda relatif stabil dibanding bulan lalu (hanya bergeser sebesar <strong>" . round(abs($diffPercent)) . "%</strong>). Ini menunjukkan konsistensi belanja Anda.";
                }
            }

            // 4. Analisis Kategori Utama (Top Category Dominance)
            if ($pengeluaran > 0 && $pengeluaranKategori->count() > 0) {
                $topKategori = $pengeluaranKategori->first();
                $topPersen = ($topKategori->total / $pengeluaran) * 100;
                if ($topPersen >= 30) {
                    $insightMessages[] = "🔍 <strong>Pengeluaran Dominan:</strong> Kategori <strong>{$topKategori->nama}</strong> mendominasi pengeluaran Anda sebesar <strong>" . round($topPersen) . "%</strong> (Rp " . number_format($topKategori->total, 0, ',', '.') . "). Evaluasi kembali apakah pengeluaran ini merupakan kebutuhan atau keinginan.";
                } else {
                    $insightMessages[] = "🔍 <strong>Distribusi Seimbang:</strong> Pengeluaran Anda tersebar merata tanpa ada satu kategori yang dominan (tertinggi <strong>{$topKategori->nama}</strong> menyumbang " . round($topPersen) . "%). Ini tanda diversifikasi belanja yang cukup baik.";
                }
            }

            // 5. Rata-Rata Pengeluaran Harian (Daily Burn Rate)
            if ($pengeluaran > 0) {
                $daysInMonth = Carbon::create($tahun, $bulan)->daysInMonth;
                if ($bulan === (int)Carbon::now()->month && $tahun === (int)Carbon::now()->year) {
                    $daysInMonth = Carbon::now()->day;
                }
                $dailyBurn = $pengeluaran / max(1, $daysInMonth);
                $insightMessages[] = "📅 <strong>Rata-rata Harian:</strong> Rata-rata pengeluaran harian Anda di periode ini adalah sekitar <strong>Rp " . number_format($dailyBurn, 0, ',', '.') . "</strong> per hari.";
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