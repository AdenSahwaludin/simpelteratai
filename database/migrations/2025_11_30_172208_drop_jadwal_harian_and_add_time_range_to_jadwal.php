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
        // Drop jadwal_harian table
        Schema::dropIfExists('jadwal_harian');

        // Add waktu_mulai and waktu_selesai to jadwal table
        Schema::table('jadwal', function (Blueprint $table) {
            $table->time('waktu_mulai')->nullable()->after('waktu');
            $table->time('waktu_selesai')->nullable()->after('waktu_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate jadwal_harian table
        Schema::create('jadwal_harian', function (Blueprint $table) {
            $table->string('id_jadwal_harian', 10)->primary();
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('kegiatan');
            $table->text('keterangan')->nullable();
            $table->string('kelas', 20);
            $table->timestamps();
        });

        // Remove waktu_mulai and waktu_selesai from jadwal
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropColumn(['waktu_mulai', 'waktu_selesai']);
        });
    }
};
