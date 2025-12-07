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
        Schema::table('jadwal', function (Blueprint $table) {
            // Remove old waktu column as we now use waktu_mulai and waktu_selesai
            $table->dropColumn('waktu');

            // Make waktu_mulai and waktu_selesai required (not nullable)
            $table->time('waktu_mulai')->nullable(false)->change();
            $table->time('waktu_selesai')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            // Re-add waktu column
            $table->time('waktu')->after('ruang');

            // Make waktu_mulai and waktu_selesai nullable again
            $table->time('waktu_mulai')->nullable()->change();
            $table->time('waktu_selesai')->nullable()->change();
        });
    }
};
