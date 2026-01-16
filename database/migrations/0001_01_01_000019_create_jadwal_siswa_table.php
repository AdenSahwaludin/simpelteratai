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
            $table->string('id_jadwal', 6); // Updated from 3 to match jadwal table
            $table->string('id_siswa', 7); // Updated from 4 to match siswa table
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
