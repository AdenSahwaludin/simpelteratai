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
        Schema::create('jadwal_siswa', function (Blueprint $table) {
            $table->string('id_jadwal', 3);
            $table->string('id_siswa', 4);
            $table->timestamps();

            $table->primary(['id_jadwal', 'id_siswa']);
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal')->onDelete('cascade');
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_siswa');
    }
};
