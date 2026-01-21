<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Drop FK constraint from siswa.id_kelas
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropForeign(['id_kelas']);
        });

        // Step 2: Refactor kelas table
        Schema::table('kelas', function (Blueprint $table) {
            // Drop primary key and add new column
            $table->renameColumn('id_kelas', 'id_kelas_old');
        });

        // Step 3: Update siswa.id_kelas to match kelas.nama_kelas values
        DB::statement('UPDATE siswa SET id_kelas = (SELECT nama_kelas FROM kelas WHERE kelas.id_kelas_old = siswa.id_kelas)');

        // Step 4: Drop kelas.id_kelas_old and rename nama_kelas
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('id_kelas_old');
            $table->renameColumn('nama_kelas', 'id_kelas');
            $table->primary('id_kelas');
        });

        // Step 5: Drop redundant kelas column from siswa
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn('kelas');
        });

        // Step 6: Re-add FK constraint with new reference
        Schema::table('siswa', function (Blueprint $table) {
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse Step 6: Drop FK
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropForeign(['id_kelas']);
        });

        // Reverse Step 5: Add back kelas column
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('kelas', 20)->after('tanggal_lahir');
        });

        // Reverse Step 4: Rename id_kelas back to nama_kelas and drop PK
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropPrimary(['id_kelas']);
            $table->renameColumn('id_kelas', 'nama_kelas');
            $table->string('id_kelas', 6)->primary();
        });

        // Reverse Step 3: Update siswa.id_kelas back to original values
        DB::statement('UPDATE siswa SET id_kelas = (SELECT id_kelas_old FROM kelas WHERE kelas.nama_kelas = siswa.id_kelas)');

        // Reverse Step 2: Restore id_kelas_old
        Schema::table('kelas', function (Blueprint $table) {
            $table->renameColumn('id_kelas_old', 'id_kelas');
        });

        // Reverse Step 1: Re-add FK constraint
        Schema::table('siswa', function (Blueprint $table) {
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('set null');
        });
    }
};
