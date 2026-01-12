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
        Schema::create('guru', function (Blueprint $table) {
            $table->string('id_guru', 6)->primary();
            $table->string('nip', 18)->unique()->nullable(); // Added: 2025_11_19_080516
            $table->string('nama', 255);
            $table->string('password', 100);
            $table->string('email', 150);
            $table->string('no_telpon', 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};
