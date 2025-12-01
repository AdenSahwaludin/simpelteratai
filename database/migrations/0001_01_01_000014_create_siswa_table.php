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
        Schema::create('siswa', function (Blueprint $table) {
            $table->string('id_siswa', 4)->primary();
            $table->string('nama', 255);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 50)->nullable();
            $table->date('tanggal_lahir');
            $table->string('kelas', 20);
            $table->string('alamat', 100);
            $table->string('id_orang_tua', 4);
            $table->timestamps();

            $table->foreign('id_orang_tua')->references('id_orang_tua')->on('orang_tua')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
