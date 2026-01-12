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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->string('id_jadwal', 6)->primary();
            $table->string('id_guru', 6);
            $table->string('id_mata_pelajaran', 6);
            $table->string('ruang', 50);
            $table->string('hari', 20); // Added: 2025_11_30_120818
            $table->time('waktu_mulai'); // Added: 2025_11_30_120818, replaced waktu (2025_12_07_125511)
            $table->time('waktu_selesai'); // Added: 2025_11_30_120818
            $table->date('tanggal_mulai')->nullable(); // Needed for generatePertemuan()
            $table->timestamps();

            $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
            $table->foreign('id_mata_pelajaran')->references('id_mata_pelajaran')->on('mata_pelajaran')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
