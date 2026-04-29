<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Status pembayaran akun
            $table->enum('status', ['active', 'pending', 'suspended'])
                  ->default('active')
                  ->after('password');

            // Paket langganan
            $table->enum('plan', ['starter', 'personal', 'profesional'])
                  ->default('starter')
                  ->after('status');

            // Kapan plan expired (null = starter/selamanya)
            $table->timestamp('plan_expires_at')
                  ->nullable()
                  ->after('plan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'plan', 'plan_expires_at']);
        });
    }
};