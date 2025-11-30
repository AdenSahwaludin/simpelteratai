<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwal_harian', function (Blueprint $table) {
            $table->string('id_jadwal_harian', 10)->primary();
            $table->date('tanggal');
            $table->string('tema')->nullable();
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->text('kegiatan');
            $table->text('catatan')->nullable();
            $table->string('kelas')->nullable(); // Untuk filter kelas tertentu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_harian');
    }
};
