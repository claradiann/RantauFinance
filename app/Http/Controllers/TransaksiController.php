<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $query = Transaksi::with(['kategori'])
            ->where('user_id', $userId);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->canAccess('filter_cari_transaksi')) {
            if ($request->filled('search')) {
                $query->where('keterangan', 'like', '%' . $request->search . '%');
            }
            if ($request->filled('tipe')) {
                $query->whereHas('kategori', function($q) use ($request) {
                    $q->where('tipe', $request->tipe);
                });
            }
        }

        $transaksi = $query->orderBy('tanggal', 'desc')->get();

        $totalPemasukan = Transaksi::where('user_id', $userId)
            ->whereHas('kategori', function($q){
                $q->where('tipe', 'pemasukan');
            })->sum('jumlah');

        $totalPengeluaran = Transaksi::where('user_id', $userId)
            ->whereHas('kategori', function($q){
                $q->where('tipe', 'pengeluaran');
            })->sum('jumlah');

        return view('transaksi.index', compact(
            'transaksi',
            'totalPemasukan',
            'totalPengeluaran'
        ));
    }

    public function exportCSV(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->canAccess('export_csv_pdf')) abort(403);

        $transaksi = Transaksi::with(['kategori'])
            ->where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        $filename = "transaksi_" . date('Y-m-d') . ".csv";
        
        return response()->streamDownload(function() use ($transaksi) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tanggal', 'Kategori', 'Tipe', 'Keterangan', 'Jumlah']);

            foreach ($transaksi as $t) {
                fputcsv($handle, [
                    $t->tanggal,
                    $t->kategori->nama,
                    ucfirst($t->kategori->tipe),
                    $t->keterangan,
                    (float)$t->jumlah
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function exportPDF(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->canAccess('export_csv_pdf')) abort(403);

        $transaksi = Transaksi::with(['kategori'])
            ->where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('transaksi.pdf', compact('transaksi', 'user'));
        return $pdf->download('laporan_transaksi_' . date('Y-m-d') . '.pdf');
    }

    public function create()
    {
        $kategori = Kategori::all();

        return view('transaksi.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
        ]);

        Transaksi::create([
            'user_id' => Auth::id(),
            'kategori_id' => $request->kategori_id,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect('/dashboard')->with('success', 'Transaksi berhasil ditambahkan!');
    }
    public function edit(int $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->user_id !== Auth::id()) {
            abort(403);
        }

        $kategori = Kategori::all();

        return view('transaksi.edit', compact('transaksi', 'kategori'));
    }

    public function update(Request $request, int $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
        ]);

        $transaksi->update([
            'kategori_id' => $request->kategori_id,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi->user_id !== Auth::id()) {
            abort(403);
        }

        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus!');
    }
}