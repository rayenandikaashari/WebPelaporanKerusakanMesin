<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('laporan_kerusakan_id')
                ->nullable()
                ->constrained('laporan_kerusakans')
                ->nullOnDelete();

            $table->string('type', 50);
            $table->string('title', 150);
            $table->text('message');
            $table->string('url', 1000)->nullable();
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'read_at']);
            $table->index('laporan_kerusakan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
