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
        Schema::create('laporan_lengkap', function (Blueprint $table) {
            $table->string('id_laporan_lengkap', 10)->primary();
            $table->string('id_siswa', 5);
            $table->string('id_guru', 5);
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->text('catatan_guru')->nullable();
            $table->text('target_pembelajaran')->nullable();
            $table->text('pencapaian')->nullable();
            $table->text('saran')->nullable();
            $table->boolean('dikirim_ke_ortu')->default(false);
            $table->timestamp('tanggal_kirim')->nullable();
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
            $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_lengkap');
    }
};
