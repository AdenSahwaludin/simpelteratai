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
        Schema::table('perilaku', function (Blueprint $table) {
            $table->date('tanggal')->after('id_guru')->nullable();
            $table->enum('sosial', ['Baik', 'Perlu dibina'])->after('tanggal')->nullable();
            $table->enum('emosional', ['Baik', 'Perlu dibina'])->after('sosial')->nullable();
            $table->enum('disiplin', ['Baik', 'Perlu dibina'])->after('emosional')->nullable();
            $table->string('file_lampiran')->after('catatan_perilaku')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perilaku', function (Blueprint $table) {
            $table->dropColumn(['tanggal', 'sosial', 'emosional', 'disiplin', 'file_lampiran']);
        });
    }
};
