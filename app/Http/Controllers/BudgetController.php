<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Kategori;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $bulan  = $request->bulan ?? Carbon::now()->month;
        $tahun  = $request->tahun ?? Carbon::now()->year;

        $budgets = Budget::with('kategori')
            ->where('user_id', $userId)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get()
            ->map(function ($b) use ($userId, $bulan, $tahun) {
                $terpakai = Transaksi::where('user_id', $userId)
                    ->where('kategori_id', $b->kategori_id)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->sum('jumlah');

                $b->terpakai  = $terpakai;
                $b->sisa      = $b->jumlah - $terpakai;
                $b->persen    = $b->jumlah > 0 ? min(100, round(($terpakai / $b->jumlah) * 100)) : 0;
                return $b;
            });

        $kategoriPengeluaran = Kategori::where('tipe', 'pengeluaran')->get();

        return view('budget.index', compact('budgets', 'kategoriPengeluaran', 'bulan', 'tahun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'jumlah'      => 'required|numeric|min:1|max:9999999999.99',
            'bulan'       => 'required|integer|min:1|max:12',
            'tahun'       => 'required|integer|min:2000',
        ], [
            'jumlah.required' => 'Jumlah budget wajib diisi.',
            'jumlah.numeric'  => 'Jumlah budget harus berupa angka.',
            'jumlah.min'      => 'Jumlah budget minimal adalah 1.',
            'jumlah.max'      => 'Jumlah budget tidak boleh lebih dari 9.999.999.999,99.',
        ]);

        Budget::updateOrCreate(
            [
                'user_id'     => Auth::id(),
                'kategori_id' => $request->kategori_id,
                'bulan'       => $request->bulan,
                'tahun'       => $request->tahun,
            ],
            ['jumlah' => $request->jumlah]
        );

        return redirect('/budget')->with('success', 'Budget berhasil disimpan!');
    }

    public function destroy(int $id)
    {
        $budget = Budget::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $budget->delete();

        return redirect('/budget')->with('success', 'Budget berhasil dihapus!');
    }
}