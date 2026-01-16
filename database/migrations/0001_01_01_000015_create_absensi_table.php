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
        Schema::create('absensi', function (Blueprint $table) {
            $table->string('id_absensi', 7)->primary();
            $table->string('id_siswa', 7);
            $table->string('id_pertemuan', 13); // Changed from id_jadwal: 2025_11_30_122604, length adjusted to 13
            $table->date('tanggal')->nullable(); // Made nullable for auto-generation
            $table->enum('status_kehadiran', ['hadir', 'izin', 'sakit', 'alpha', 'belum_absen']); // Updated to match app usage
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
            $table->foreign('id_pertemuan')->references('id_pertemuan')->on('pertemuan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
