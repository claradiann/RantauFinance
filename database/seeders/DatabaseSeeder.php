<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /* =========================
           USERS (FREE & PRO)
        ========================== */
        DB::table('users')->insert([
            [
                'name' => 'User Free',
                'email' => 'free@demo.com',
                'password' => Hash::make('123456'),
                'status' => 'active',
                'plan' => 'free',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'User Pro',
                'email' => 'pro@demo.com',
                'password' => Hash::make('123456'),
                'status' => 'active',
                'plan' => 'pro',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        /* =========================
           KATEGORI (GLOBAL)
        ========================== */
        DB::table('kategori')->insert([
            // pemasukan
            ['nama' => 'Gaji', 'tipe' => 'pemasukan', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Freelance', 'tipe' => 'pemasukan', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Bonus', 'tipe' => 'pemasukan', 'created_at' => now(), 'updated_at' => now()],

            // pengeluaran
            ['nama' => 'Makan', 'tipe' => 'pengeluaran', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Transport', 'tipe' => 'pengeluaran', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Kos / Rumah', 'tipe' => 'pengeluaran', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Belanja', 'tipe' => 'pengeluaran', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Hiburan', 'tipe' => 'pengeluaran', 'created_at' => now(), 'updated_at' => now()],
        ]);

        /* =========================
           TRANSAKSI DUMMY (user 1)
        ========================== */
        DB::table('transaksi')->insert([
            [
                'user_id' => 1,
                'kategori_id' => 1,
                'jumlah' => 5000000,
                'tanggal' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'kategori_id' => 4,
                'jumlah' => 150000,
                'tanggal' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        /* =========================
           BUDGET DUMMY
        ========================== */
        DB::table('budget')->insert([
            [
                'user_id' => 1,
                'kategori_id' => 4,
                'jumlah' => 1000000,
                'bulan' => now()->month,
                'tahun' => now()->year,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}