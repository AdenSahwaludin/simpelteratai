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
        Schema::create('komentar', function (Blueprint $table) {
            $table->string('id_komentar', 7)->primary();
            $table->string('id_orang_tua', 7)->nullable(); // Made nullable: 2025_12_05_093200
            $table->string('id_laporan_lengkap', 7)->nullable(); // Added: 2025_12_05_082950
            $table->string('id_guru', 6)->nullable(); // Added: 2025_12_05_082950
            $table->string('parent_id', 7)->nullable(); // Added: 2025_12_05_082950 (for nested comments)
            $table->text('komentar');
            $table->timestamps();

            $table->foreign('id_orang_tua')->references('id_orang_tua')->on('orang_tua')->onDelete('cascade');
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
        Schema::dropIfExists('komentar');
    }
};
