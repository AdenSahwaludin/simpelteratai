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
        Schema::table('komentar', function (Blueprint $table) {
            $table->string('id_laporan_lengkap', 10)->nullable()->after('id_orang_tua');
            $table->string('id_guru', 4)->nullable()->after('id_laporan_lengkap');
            $table->string('parent_id', 4)->nullable()->after('id_guru');

            $table->foreign('id_laporan_lengkap')->references('id_laporan_lengkap')->on('laporan_lengkap')->onDelete('cascade');
            $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
            $table->foreign('parent_id')->references('id_komentar')->on('komentar')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komentar', function (Blueprint $table) {
            $table->dropForeign(['id_laporan_lengkap']);
            $table->dropForeign(['id_guru']);
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['id_laporan_lengkap', 'id_guru', 'parent_id']);
        });
    }
};
