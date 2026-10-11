<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_maintenances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mesin_id')
                ->constrained('mesins')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('teknisi_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->date('tanggal');
            $table->enum('status', [
                'Belum Dicek',
                'Sudah Dicek'
            ])->default('Belum Dicek');

            $table->time('waktu')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->unique(['mesin_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_maintenances');
    }
};