<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategori')->insert([
            ['nama' => 'Gaji', 'tipe' => 'pemasukan'],
            ['nama' => 'Freelance', 'tipe' => 'pemasukan'],
            ['nama' => 'Investasi', 'tipe' => 'pemasukan'],

            ['nama' => 'Makan', 'tipe' => 'pengeluaran'],
            ['nama' => 'Transport', 'tipe' => 'pengeluaran'],
            ['nama' => 'Kos / Rumah', 'tipe' => 'pengeluaran'],
            ['nama' => 'Belanja', 'tipe' => 'pengeluaran'],
            ['nama' => 'Hiburan', 'tipe' => 'pengeluaran'],
        ]);
    }
}
