<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah enum status_kehadiran untuk menambahkan 'belum_absen'
        DB::statement("ALTER TABLE absensi MODIFY status_kehadiran ENUM('belum_absen', 'hadir', 'izin', 'sakit', 'alpha') NOT NULL DEFAULT 'belum_absen'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum lama
        DB::statement("ALTER TABLE absensi MODIFY status_kehadiran ENUM('hadir', 'izin', 'sakit', 'alpha') NOT NULL");
    }
};
