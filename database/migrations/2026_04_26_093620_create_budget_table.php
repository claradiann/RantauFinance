<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('budget', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            //$table->foreignId('kategori_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kategori_id')
                ->constrained('kategori')
                ->cascadeOnDelete();
            $table->decimal('jumlah', 12, 2);
            $table->unsignedTinyInteger('bulan'); // 1–12
            $table->year('tahun');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget');
    }
};