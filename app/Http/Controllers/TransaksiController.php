<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $transaksi = Transaksi::with(['kategori'])
            ->where('user_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->get();

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
}