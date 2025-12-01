<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Hapus semua data absensi lama dan laporan perkembangan (karena dependent)
        DB::table('laporan_perkembangan')->truncate();
        DB::table('absensi')->truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Schema::table('absensi', function (Blueprint $table) {
            // Drop foreign key dan kolom lama
            $table->dropForeign(['id_jadwal']);
            $table->dropColumn(['id_jadwal', 'tanggal']);

            // Tambah kolom baru
            $table->string('id_pertemuan', 10)->after('id_siswa');
            $table->foreign('id_pertemuan')->references('id_pertemuan')->on('pertemuan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropForeign(['id_pertemuan']);
            $table->dropColumn('id_pertemuan');

            $table->string('id_jadwal', 3)->after('id_siswa');
            $table->date('tanggal')->after('id_jadwal');
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal')->onDelete('cascade');
        });
    }
};
