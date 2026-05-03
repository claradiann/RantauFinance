<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // STARTER — gratis, di dunia nyata user bikin password sendiri saat daftar
        // Password di sini hanya untuk keperluan testing/demo lokal
        User::firstOrCreate(['email' => 'starter@demo.com'], [
            'name'     => 'Demo Starter',
            'password' => Hash::make('password123'),
            'plan'     => 'starter',
            'status'   => 'active',
            // plan_expires_at tidak perlu — starter tidak pernah expired
        ]);

        // PERSONAL — di dunia nyata password dikirim via email setelah admin konfirmasi bayar
        // Password di sini hanya untuk keperluan testing/demo lokal
        User::firstOrCreate(['email' => 'personal@demo.com'], [
            'name'            => 'Demo Personal',
            'password'        => Hash::make('password123'),
            'plan'            => 'personal',
            'status'          => 'active',
            'plan_expires_at' => now()->addMonth(),
        ]);

        // PROFESIONAL — di dunia nyata password dikirim via email setelah admin konfirmasi bayar
        // Password di sini hanya untuk keperluan testing/demo lokal
        User::firstOrCreate(['email' => 'pro@demo.com'], [
            'name'            => 'Demo Profesional',
            'password'        => Hash::make('password123'),
            'plan'            => 'profesional',
            'status'          => 'active',
            'plan_expires_at' => now()->addMonth(),
        ]);

        // ADMIN
        User::firstOrCreate(['email' => 'admin@rantaufinance.com'], [
            'name'     => 'Admin',
            'password' => Hash::make('adminpassword123'),
            'plan'     => 'admin',
            'status'   => 'active',
        ]);

        $this->call(KategoriSeeder::class);
    }
}