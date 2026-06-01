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
        Schema::dropIfExists('jadwal_siswa');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('jadwal_siswa', function (Blueprint $table) {
            $table->id();
            $table->string('id_jadwal', 50);
            $table->string('id_siswa', 50);
            $table->timestamps();

            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal')->onDelete('cascade');
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
            $table->unique(['id_jadwal', 'id_siswa']);
        });
    }
};
