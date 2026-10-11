<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_kerusakans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mesin_id')
                ->constrained('mesins')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('operator_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('teknisi_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('judul');
            $table->text('deskripsi');
            $table->longBlob('foto')->nullable();

            $table->enum('status', [
                'Menunggu Ditangani',
                'Sedang Diperbaiki',
                'Selesai Diperbaiki'
            ])->default('Menunggu Ditangani');

            $table->text('hasil_perbaikan')->nullable();
            $table->text('sparepart')->nullable();
            $table->timestamp('selesai_pada')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_kerusakans');
    }
};