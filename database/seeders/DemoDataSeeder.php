<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Admin;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Komentar;
use App\Models\LaporanPerkembangan;
use App\Models\MataPelajaran;
use App\Models\OrangTua;
use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin (5 data)
        $admins = [
            ['id_admin' => 'A00001', 'nama' => 'Admin Utama', 'email' => 'admin@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '081234567801'],
            ['id_admin' => 'A00002', 'nama' => 'Admin TU', 'email' => 'tu@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '081234567802'],
            ['id_admin' => 'A00003', 'nama' => 'Admin Keuangan', 'email' => 'keuangan@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '081234567803'],
            ['id_admin' => 'A00004', 'nama' => 'Admin Kurikulum', 'email' => 'kurikulum@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '081234567804'],
            ['id_admin' => 'A00005', 'nama' => 'Admin Kesiswaan', 'email' => 'kesiswaan@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '081234567805'],
        ];

        foreach ($admins as $admin) {
            Admin::firstOrCreate(['id_admin' => $admin['id_admin']], $admin);
        }

        // Guru (5 data)
        $gurus = [
            ['id_guru' => 'G00001', 'nip' => '198905151020051001', 'nama' => 'Budi Santoso', 'email' => 'budi@simpel.com', 'password' => Hash::make('  '), 'no_telpon' => '082134567801'],
            ['id_guru' => 'G00002', 'nip' => '199003201020052002', 'nama' => 'Siti Aminah', 'email' => 'siti@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '082134567802'],
            ['id_guru' => 'G00003', 'nip' => '198702101020053003', 'nama' => 'Ahmad Dahlan', 'email' => 'ahmad@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '082134567803'],
            ['id_guru' => 'G00004', 'nip' => '199105151020054004', 'nama' => 'Dewi Sartika', 'email' => 'dewi@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '082134567804'],
            ['id_guru' => 'G00005', 'nip' => '198808201020055005', 'nama' => 'Kartini Wijaya', 'email' => 'kartini@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '082134567805'],
        ];

        foreach ($gurus as $guru) {
            Guru::firstOrCreate(['id_guru' => $guru['id_guru']], $guru);
        }

        // Orang Tua (5 data)
        $orangTuas = [
            ['id_orang_tua' => 'O000003', 'nama' => 'Suparman', 'email' => 'suparman@gmail.com', 'password' => Hash::make('password'), 'no_telpon' => '083234567801'],
            ['id_orang_tua' => 'O000004', 'nama' => 'Supardi', 'email' => 'supardi@gmail.com', 'password' => Hash::make('password'), 'no_telpon' => '083234567802'],
            ['id_orang_tua' => 'O000005', 'nama' => 'Sumanto', 'email' => 'sumanto@gmail.com', 'password' => Hash::make('password'), 'no_telpon' => '083234567803'],
            ['id_orang_tua' => 'O000006', 'nama' => 'Sukirman', 'email' => 'sukirman@gmail.com', 'password' => Hash::make('password'), 'no_telpon' => '083234567804'],
            ['id_orang_tua' => 'O000007', 'nama' => 'Sutarjo', 'email' => 'sutarjo@gmail.com', 'password' => Hash::make('password'), 'no_telpon' => '083234567805'],
        ];

        foreach ($orangTuas as $orangTua) {
            OrangTua::firstOrCreate(['id_orang_tua' => $orangTua['id_orang_tua']], $orangTua);
        }

        // Siswa (5 data)
        $siswaData = [
            ['id_siswa' => 'S000001', 'nama' => 'Andi Pratama', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'Bandung', 'tanggal_lahir' => '2013-05-15', 'kelas' => '5A', 'alamat' => 'Jl. Merdeka No. 10', 'id_orang_tua' => 'O000003'],
            ['id_siswa' => 'S000002', 'nama' => 'Budi Setiawan', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'Sumedang', 'tanggal_lahir' => '2013-08-22', 'kelas' => '5A', 'alamat' => 'Jl. Sudirman No. 20', 'id_orang_tua' => 'O000004'],
            ['id_siswa' => 'S000003', 'nama' => 'Citra Dewi', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'Cirebon', 'tanggal_lahir' => '2013-03-10', 'kelas' => '5B', 'alamat' => 'Jl. Gatot Subroto No. 30', 'id_orang_tua' => 'O000005'],
            ['id_siswa' => 'S000004', 'nama' => 'Doni Ramadhan', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'Bandung', 'tanggal_lahir' => '2013-11-30', 'kelas' => '5B', 'alamat' => 'Jl. Ahmad Yani No. 40', 'id_orang_tua' => 'O000006'],
            ['id_siswa' => 'S000005', 'nama' => 'Eka Putri', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'Jakarta', 'tanggal_lahir' => '2012-07-18', 'kelas' => '6A', 'alamat' => 'Jl. Diponegoro No. 50', 'id_orang_tua' => 'O000007'],
        ];

        foreach ($siswaData as $siswa) {
            Siswa::firstOrCreate(['id_siswa' => $siswa['id_siswa']], $siswa);
        }

        // Mata Pelajaran (5 data)
        $mataPelajarans = [
            ['id_mata_pelajaran' => 'MP0001', 'nama_mapel' => 'Bahasa dan Komunikasi'],
            ['id_mata_pelajaran' => 'MP0002', 'nama_mapel' => 'Perkembangan Kognitif'],
            ['id_mata_pelajaran' => 'MP0003', 'nama_mapel' => 'Seni dan Kreativitas'],
            ['id_mata_pelajaran' => 'MP0004', 'nama_mapel' => 'Sosial dan Emosional'],
            ['id_mata_pelajaran' => 'MP0005', 'nama_mapel' => 'Pengenalan Lingkungan'],
        ];

        foreach ($mataPelajarans as $mapel) {
            MataPelajaran::firstOrCreate(['id_mata_pelajaran' => $mapel['id_mata_pelajaran']], $mapel);
        }

        // Jadwal - gunakan waktu_mulai dan waktu_selesai (bukan waktu)
        // Data jadwal butuh: hari, kelas, tanggal_mulai, waktu_mulai, waktu_selesai
        // Dan akan auto-generate 14 pertemuan + assign semua siswa

        $jadwals = [
            ['id_jadwal' => 'JD0001', 'id_guru' => 'G00001', 'id_mata_pelajaran' => 'MP0001', 'waktu_mulai' => '07:00:00', 'waktu_selesai' => '08:00:00', 'hari' => 'Senin', 'ruang' => '5A', 'tanggal_mulai' => '2025-01-06'],
            ['id_jadwal' => 'JD0002', 'id_guru' => 'G00002', 'id_mata_pelajaran' => 'MP0002', 'waktu_mulai' => '08:30:00', 'waktu_selesai' => '09:30:00', 'hari' => 'Selasa', 'ruang' => '5A', 'tanggal_mulai' => '2025-01-07'],
            ['id_jadwal' => 'JD0003', 'id_guru' => 'G00003', 'id_mata_pelajaran' => 'MP0003', 'waktu_mulai' => '10:00:00', 'waktu_selesai' => '11:00:00', 'hari' => 'Rabu', 'ruang' => '5B', 'tanggal_mulai' => '2025-01-08'],
        ];

        foreach ($jadwals as $data) {
            $jadwal = Jadwal::firstOrCreate(['id_jadwal' => $data['id_jadwal']], $data);
            // Auto-generate 14 pertemuan + assign siswa
            if ($jadwal->wasRecentlyCreated) {
                $jadwal->generatePertemuan();
            }
        }

        // Absensi (DISABLED - sekarang auto-generated saat create jadwal)
        // Absensi sekarang link ke pertemuan, bukan jadwal
        /*
        $absensis = [
            ['id_absensi' => 'A001', 'id_siswa' => 'S001', 'id_pertemuan' => 'JD1-P01', 'status_kehadiran' => 'hadir'],
            ['id_absensi' => 'A002', 'id_siswa' => 'S002', 'id_pertemuan' => 'JD2-P01', 'status_kehadiran' => 'hadir'],
        ];

        foreach ($absensis as $absensi) {
            Absensi::firstOrCreate(['id_absensi' => $absensi['id_absensi']], $absensi);
        }
        */

        /*
        foreach ($absensis as $absensi) {
            Absensi::firstOrCreate(['id_absensi' => $absensi['id_absensi']], $absensi);
        }
        */

        /*
        // Laporan Perkembangan (5 data)
        $laporans = [
            ['id_laporan' => 'LP1', 'id_siswa' => 'S001', 'id_mata_pelajaran' => 'MP1', 'nilai' => 85, 'id_absensi' => 'A001', 'komentar' => 'Sangat baik'],
            ['id_laporan' => 'LP2', 'id_siswa' => 'S002', 'id_mata_pelajaran' => 'MP2', 'nilai' => 90, 'id_absensi' => 'A002', 'komentar' => 'Excellent'],
            ['id_laporan' => 'LP3', 'id_siswa' => 'S003', 'id_mata_pelajaran' => 'MP3', 'nilai' => 78, 'id_absensi' => 'A003', 'komentar' => 'Baik, perlu ditingkatkan'],
            ['id_laporan' => 'LP4', 'id_siswa' => 'S004', 'id_mata_pelajaran' => 'MP4', 'nilai' => 88, 'id_absensi' => 'A004', 'komentar' => 'Sangat memuaskan'],
            ['id_laporan' => 'LP5', 'id_siswa' => 'S005', 'id_mata_pelajaran' => 'MP5', 'nilai' => 92, 'id_absensi' => 'A005', 'komentar' => 'Luar biasa'],
        ];

        foreach ($laporans as $laporan) {
            LaporanPerkembangan::firstOrCreate(['id_laporan' => $laporan['id_laporan']], $laporan);
        }
        */
        $this->command->info('✅ Demo data seeded successfully!');
        $this->command->info('📊 Summary:');
        $this->command->info('   - Admins: '.Admin::count());
        $this->command->info('   - Guru: '.Guru::count());
        $this->command->info('   - Orang Tua: '.OrangTua::count());
        $this->command->info('   - Siswa: '.Siswa::count());
        $this->command->info('   - Mata Pelajaran: '.MataPelajaran::count());
        // $this->command->info('   - Jadwal: '.Jadwal::count());
        // $this->command->info('   - Absensi: '.Absensi::count());
        // $this->command->info('   - Laporan: '.LaporanPerkembangan::count());
    }
}
