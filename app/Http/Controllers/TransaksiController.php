<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::with(['user', 'kategori'])->get();

        $totalPemasukan = Transaksi::whereHas('kategori', function($q){
            $q->where('tipe', 'pemasukan');
        })->sum('jumlah');

        $totalPengeluaran = Transaksi::whereHas('kategori', function($q){
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
        $users = User::all();

        return view('transaksi.create', compact('kategori','users'));
    }

    public function store(Request $request)
    {
        Transaksi::create([
            'user_id' => $request->user_id,
            'kategori_id' => $request->kategori_id,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
        ]);

        return redirect('/transaksi');
    }
}