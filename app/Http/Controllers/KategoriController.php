<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $pemasukan = Kategori::where('tipe', 'pemasukan')
            ->withCount([
                'transaksi as user_transaksi_count' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                },
                'transaksi as total_transaksi_count'
            ])
            ->get();

        $pengeluaran = Kategori::where('tipe', 'pengeluaran')
            ->withCount([
                'transaksi as user_transaksi_count' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                },
                'transaksi as total_transaksi_count'
            ])
            ->get();

        return view('kategori.index', compact('pemasukan', 'pengeluaran'));
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if (! $user->canAccess('kategori_custom_unlimited')) {
            return redirect()->route('kategori.index')->with('error', 'Fitur Kategori Custom hanya tersedia di paket Profesional.');
        }

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

    public function destroy(int $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->canAccess('kategori_custom_unlimited')) {
            return redirect()->route('kategori.index')->with('error', 'Fitur Kategori Custom hanya tersedia di paket Profesional.');
        }

        $kategori = Kategori::findOrFail($id);

        // Cegah hapus kalau masih ada transaksi
        if ($kategori->transaksi()->count() > 0) {
            return redirect('/kategori')->with('error', 'Kategori tidak bisa dihapus karena masih digunakan di transaksi.');
        }

        $kategori->delete();

        return redirect('/kategori')->with('success', 'Kategori berhasil dihapus!');
    }
}