<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Paket yang dibeli
            $table->enum('plan', ['personal', 'profesional']);

            // Nominal sesuai plan
            $table->decimal('nominal', 12, 2);

            // Metode pembayaran
            $table->enum('metode', ['qris', 'transfer'])->default('transfer');

            // Bukti transfer (path file yang diupload)
            $table->string('bukti_path')->nullable();

            // Status konfirmasi admin
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');

            // Catatan dari admin (alasan reject, dll)
            $table->text('catatan_admin')->nullable();

            // Siapa admin yang konfirmasi
            $table->foreignId('confirmed_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamp('confirmed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};