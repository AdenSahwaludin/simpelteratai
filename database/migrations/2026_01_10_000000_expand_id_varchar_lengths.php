<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Expand all ID varchar lengths by +3 to accommodate more records
     *
     * Changes:
     * - orang_tua.id_orang_tua: 4 → 7
     * - siswa.id_siswa: 4 → 7
     * - guru.id_guru: 3 → 6
     * - jadwal.id_jadwal: 3 → 6
     * - mata_pelajaran.id_mata_pelajaran: 3 → 6
     * - komentar.id_komentar: 4 → 7
     * - perilaku.id_perilaku: 3 → 6
     * - pengumuman.id_pengumuman: 3 → 6
     * - laporan_lengkap.id_laporan_lengkap: 10 → 13
     * - absensi.id_absensi: 4 → 7
     */
    public function up(): void
    {
        // Expand orang_tua.id_orang_tua
        Schema::table('orang_tua', function (Blueprint $table) {
            $table->string('id_orang_tua', 7)->change();
        });

        // Expand siswa table
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('id_siswa', 7)->change();
            $table->string('id_orang_tua', 7)->change();
        });

        // Expand guru.id_guru
        Schema::table('guru', function (Blueprint $table) {
            $table->string('id_guru', 6)->change();
        });

        // Expand jadwal table
        Schema::table('jadwal', function (Blueprint $table) {
            $table->string('id_jadwal', 6)->change();
            $table->string('id_guru', 6)->change();
            $table->string('id_mata_pelajaran', 6)->change();
        });

        // Expand mata_pelajaran.id_mata_pelajaran
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->string('id_mata_pelajaran', 6)->change();
        });

        // Expand komentar table
        Schema::table('komentar', function (Blueprint $table) {
            $table->string('id_komentar', 7)->change();
            $table->string('id_orang_tua', 7)->change();
        });

        // Expand perilaku table
        Schema::table('perilaku', function (Blueprint $table) {
            $table->string('id_perilaku', 6)->change();
            $table->string('id_siswa', 7)->change();
        });

        // Expand pengumuman table
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->string('id_pengumuman', 6)->change();
        });

        // Expand laporan_lengkap table
        Schema::table('laporan_lengkap', function (Blueprint $table) {
            $table->string('id_laporan_lengkap', 13)->change();
            $table->string('id_siswa', 7)->change();
            $table->string('id_guru', 6)->change();
        });

        // Expand absensi table
        Schema::table('absensi', function (Blueprint $table) {
            $table->string('id_absensi', 7)->change();
            $table->string('id_siswa', 7)->change();
            $table->string('id_pertemuan', 13)->change();
        });

        // Expand laporan_perkembangan table
        Schema::table('laporan_perkembangan', function (Blueprint $table) {
            $table->string('id_laporan', 6)->change();
            $table->string('id_siswa', 7)->change();
            $table->string('id_mata_pelajaran', 6)->change();
            $table->string('id_absensi', 7)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original lengths
        Schema::table('orang_tua', function (Blueprint $table) {
            $table->string('id_orang_tua', 4)->change();
        });

        Schema::table('siswa', function (Blueprint $table) {
            $table->string('id_siswa', 4)->change();
            $table->string('id_orang_tua', 4)->change();
        });

        Schema::table('guru', function (Blueprint $table) {
            $table->string('id_guru', 3)->change();
        });

        Schema::table('jadwal', function (Blueprint $table) {
            $table->string('id_jadwal', 3)->change();
            $table->string('id_guru', 3)->change();
            $table->string('id_mata_pelajaran', 3)->change();
        });

        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->string('id_mata_pelajaran', 3)->change();
        });

        Schema::table('komentar', function (Blueprint $table) {
            $table->string('id_komentar', 4)->change();
            $table->string('id_orang_tua', 4)->change();
        });

        Schema::table('perilaku', function (Blueprint $table) {
            $table->string('id_perilaku', 3)->change();
            $table->string('id_siswa', 4)->change();
        });

        Schema::table('pengumuman', function (Blueprint $table) {
            $table->string('id_pengumuman', 3)->change();
        });

        Schema::table('laporan_lengkap', function (Blueprint $table) {
            $table->string('id_laporan_lengkap', 10)->change();
            $table->string('id_siswa', 5)->change();
            $table->string('id_guru', 5)->change();
        });

        Schema::table('absensi', function (Blueprint $table) {
            $table->string('id_absensi', 4)->change();
            $table->string('id_siswa', 4)->change();
            $table->string('id_pertemuan', 10)->change();
        });

        Schema::table('laporan_perkembangan', function (Blueprint $table) {
            $table->string('id_laporan', 3)->change();
            $table->string('id_siswa', 4)->change();
            $table->string('id_mata_pelajaran', 3)->change();
            $table->string('id_absensi', 4)->nullable()->change();
        });
    }
};
