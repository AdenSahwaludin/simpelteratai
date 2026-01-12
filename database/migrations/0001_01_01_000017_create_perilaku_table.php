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
        Schema::create('perilaku', function (Blueprint $table) {
            $table->string('id_perilaku', 6)->primary();
            $table->string('id_siswa', 7);
            $table->text('catatan_perilaku')->nullable();
            $table->integer('sosial')->nullable(); // Added: 2024_11_24_000001 (1-5 scale)
            $table->integer('emosional')->nullable(); // Added: 2024_11_24_000001 (1-5 scale)
            $table->integer('disiplin')->nullable(); // Added: 2024_11_24_000001 (1-5 scale)
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perilaku');
    }
};
