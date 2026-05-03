<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $pemasukan   = Kategori::where('tipe', 'pemasukan')->get();
        $pengeluaran = Kategori::where('tipe', 'pengeluaran')->get();

        return view('kategori.index', compact('pemasukan', 'pengeluaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50|unique:kategori,nama',
            'tipe' => 'required|in:pemasukan,pengeluaran',
        ]);

        Kategori::create([
            'nama' => $request->nama,
            'tipe' => $request->tipe,
        ]);

        return redirect('/kategori')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        // Cegah hapus kalau masih ada transaksi
        if ($kategori->transaksi()->count() > 0) {
            return redirect('/kategori')->with('error', 'Kategori tidak bisa dihapus karena masih digunakan di transaksi.');
        }

        $kategori->delete();

        return redirect('/kategori')->with('success', 'Kategori berhasil dihapus!');
    }
}