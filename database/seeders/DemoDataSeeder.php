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
use App\Models\Pengumuman;
use App\Models\Perilaku;
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
            ['id_admin' => 'A01', 'nama' => 'Admin Utama', 'email' => 'admin@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '081234567801'],
            ['id_admin' => 'A02', 'nama' => 'Admin TU', 'email' => 'tu@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '081234567802'],
            ['id_admin' => 'A03', 'nama' => 'Admin Keuangan', 'email' => 'keuangan@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '081234567803'],
            ['id_admin' => 'A04', 'nama' => 'Admin Kurikulum', 'email' => 'kurikulum@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '081234567804'],
            ['id_admin' => 'A05', 'nama' => 'Admin Kesiswaan', 'email' => 'kesiswaan@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '081234567805'],
        ];

        foreach ($admins as $admin) {
            Admin::firstOrCreate(['id_admin' => $admin['id_admin']], $admin);
        }

        // Guru (5 data)
        $gurus = [
            ['id_guru' => 'G01', 'nip' => '198905151020051001', 'nama' => 'Budi Santoso', 'email' => 'budi@simpel.com', 'password' => Hash::make('  '), 'no_telpon' => '082134567801'],
            ['id_guru' => 'G02', 'nip' => '199003201020052002', 'nama' => 'Siti Aminah', 'email' => 'siti@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '082134567802'],
            ['id_guru' => 'G03', 'nip' => '198702101020053003', 'nama' => 'Ahmad Dahlan', 'email' => 'ahmad@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '082134567803'],
            ['id_guru' => 'G04', 'nip' => '199105151020054004', 'nama' => 'Dewi Sartika', 'email' => 'dewi@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '082134567804'],
            ['id_guru' => 'G05', 'nip' => '198808201020055005', 'nama' => 'Kartini Wijaya', 'email' => 'kartini@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '082134567805'],
        ];

        foreach ($gurus as $guru) {
            Guru::firstOrCreate(['id_guru' => $guru['id_guru']], $guru);
        }

        // Orang Tua (5 data)
        $orangTuas = [
            ['id_orang_tua' => 'O003', 'nama' => 'Suparman', 'email' => 'suparman@gmail.com', 'password' => Hash::make('password'), 'no_telpon' => '083234567801'],
            ['id_orang_tua' => 'O004', 'nama' => 'Supardi', 'email' => 'supardi@gmail.com', 'password' => Hash::make('password'), 'no_telpon' => '083234567802'],
            ['id_orang_tua' => 'O005', 'nama' => 'Sumanto', 'email' => 'sumanto@gmail.com', 'password' => Hash::make('password'), 'no_telpon' => '083234567803'],
            ['id_orang_tua' => 'O006', 'nama' => 'Sukirman', 'email' => 'sukirman@gmail.com', 'password' => Hash::make('password'), 'no_telpon' => '083234567804'],
            ['id_orang_tua' => 'O007', 'nama' => 'Sutarjo', 'email' => 'sutarjo@gmail.com', 'password' => Hash::make('password'), 'no_telpon' => '083234567805'],
        ];

        foreach ($orangTuas as $orangTua) {
            OrangTua::firstOrCreate(['id_orang_tua' => $orangTua['id_orang_tua']], $orangTua);
        }

        // Siswa (5 data)
        $siswaData = [
            ['id_siswa' => 'S001', 'nama' => 'Andi Pratama', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'Bandung', 'tanggal_lahir' => '2013-05-15', 'kelas' => '5A', 'alamat' => 'Jl. Merdeka No. 10', 'id_orang_tua' => 'O003'],
            ['id_siswa' => 'S002', 'nama' => 'Budi Setiawan', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'Sumedang', 'tanggal_lahir' => '2013-08-22', 'kelas' => '5A', 'alamat' => 'Jl. Sudirman No. 20', 'id_orang_tua' => 'O004'],
            ['id_siswa' => 'S003', 'nama' => 'Citra Dewi', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'Cirebon', 'tanggal_lahir' => '2013-03-10', 'kelas' => '5B', 'alamat' => 'Jl. Gatot Subroto No. 30', 'id_orang_tua' => 'O005'],
            ['id_siswa' => 'S004', 'nama' => 'Doni Ramadhan', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'Bandung', 'tanggal_lahir' => '2013-11-30', 'kelas' => '5B', 'alamat' => 'Jl. Ahmad Yani No. 40', 'id_orang_tua' => 'O006'],
            ['id_siswa' => 'S005', 'nama' => 'Eka Putri', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'Jakarta', 'tanggal_lahir' => '2012-07-18', 'kelas' => '6A', 'alamat' => 'Jl. Diponegoro No. 50', 'id_orang_tua' => 'O007'],
        ];

        foreach ($siswaData as $siswa) {
            Siswa::firstOrCreate(['id_siswa' => $siswa['id_siswa']], $siswa);
        }

        // Mata Pelajaran (5 data)
        $mataPelajarans = [
            ['id_mata_pelajaran' => 'MP1', 'nama_mapel' => 'Bahasa dan Komunikasi'],
            ['id_mata_pelajaran' => 'MP2', 'nama_mapel' => 'Perkembangan Kognitif'],
            ['id_mata_pelajaran' => 'MP3', 'nama_mapel' => 'Seni dan Kreativitas'],
            ['id_mata_pelajaran' => 'MP4', 'nama_mapel' => 'Sosial dan Emosional'],
            ['id_mata_pelajaran' => 'MP5', 'nama_mapel' => 'Pengenalan Lingkungan'],
        ];

        foreach ($mataPelajarans as $mapel) {
            MataPelajaran::firstOrCreate(['id_mata_pelajaran' => $mapel['id_mata_pelajaran']], $mapel);
        }

        // Jadwal (DISABLED - gunakan form create jadwal untuk auto-generate 14 pertemuan)
        // Data jadwal sekarang butuh: hari, kelas, tanggal_mulai
        // Dan akan auto-generate 14 pertemuan + assign semua siswa
        /*
        $jadwals = [
            ['id_jadwal' => 'JD1', 'id_guru' => 'G01', 'id_mata_pelajaran' => 'MP1', 'waktu' => '07:00:00', 'hari' => 'Senin', 'kelas' => '5A', 'ruang' => '5A', 'tanggal_mulai' => '2025-01-06'],
            ['id_jadwal' => 'JD2', 'id_guru' => 'G02', 'id_mata_pelajaran' => 'MP2', 'waktu' => '08:30:00', 'hari' => 'Selasa', 'kelas' => '5A', 'ruang' => '5A', 'tanggal_mulai' => '2025-01-07'],
            ['id_jadwal' => 'JD3', 'id_guru' => 'G03', 'id_mata_pelajaran' => 'MP3', 'waktu' => '10:00:00', 'hari' => 'Rabu', 'kelas' => '5B', 'ruang' => '5B', 'tanggal_mulai' => '2025-01-08'],
        ];

        foreach ($jadwals as $data) {
            $jadwal = Jadwal::firstOrCreate(['id_jadwal' => $data['id_jadwal']], $data);
            // Auto-generate 14 pertemuan + assign siswa
            if ($jadwal->wasRecentlyCreated) {
                $jadwal->generatePertemuan();
            }
        }
        */

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

        // Perilaku (5 data)
        $perilakus = [
            ['id_perilaku' => 'PR1', 'id_siswa' => 'S001', 'catatan_perilaku' => 'Ananda hari ini menunjukkan sikap kerjasama yang baik dan membantu teman yang kesulitan'],
            ['id_perilaku' => 'PR2', 'id_siswa' => 'S002', 'catatan_perilaku' => 'Ananda masih perlu dibimbing untuk fokus saat kegiatan belajar karena mudah teralihkan'],
            ['id_perilaku' => 'PR3', 'id_siswa' => 'S003', 'catatan_perilaku' => 'Ananda mampu mengikuti instruksi guru dengan baik dan menunjukkan disiplin selama kegiatan berlangsung'],
            ['id_perilaku' => 'PR4', 'id_siswa' => 'S004', 'catatan_perilaku' => 'Ananda tampak kurang percaya diri saat diminta maju ke depan kelas, perlu diberikan motivasi lebih'],
            ['id_perilaku' => 'PR5', 'id_siswa' => 'S005', 'catatan_perilaku' => 'Ananda sangat antusias mengikuti kegiatan seni dan menunjukkan kreativitas yang tinggi'],
        ];

        foreach ($perilakus as $perilaku) {
            Perilaku::firstOrCreate(['id_perilaku' => $perilaku['id_perilaku']], $perilaku);
        }

        // Pengumuman (5 data)
        $pengumumans = [
            ['id_pengumuman' => 'PG1', 'judul' => 'Libur Semester Ganjil', 'isi' => 'Libur semester ganjil akan dimulai tanggal 20 Desember 2025', 'tanggal' => '2025-11-18', 'id_admin' => 'A01', 'publikasi' => true],
            ['id_pengumuman' => 'PG2', 'judul' => 'Rapat Orang Tua', 'isi' => 'Rapat orang tua akan diadakan pada tanggal 25 November 2025 pukul 09.00 WIB', 'tanggal' => '2025-11-18', 'id_admin' => 'A02', 'publikasi' => true],
            ['id_pengumuman' => 'PG3', 'judul' => 'Ujian Akhir Semester', 'isi' => 'Ujian akhir semester akan dilaksanakan tanggal 1-10 Desember 2025', 'tanggal' => '2025-11-18', 'id_admin' => 'A03', 'publikasi' => true],
            ['id_pengumuman' => 'PG4', 'judul' => 'Penerimaan Siswa Baru', 'isi' => 'Pendaftaran siswa baru tahun ajaran 2026/2027 dibuka mulai 1 Januari 2026', 'tanggal' => '2025-11-18', 'id_admin' => 'A04', 'publikasi' => false],
            ['id_pengumuman' => 'PG5', 'judul' => 'Kegiatan Ekstrakurikuler', 'isi' => 'Pendaftaran ekstrakurikuler dibuka untuk semester genap', 'tanggal' => '2025-11-18', 'id_admin' => 'A05', 'publikasi' => true],
        ];

        foreach ($pengumumans as $pengumuman) {
            Pengumuman::firstOrCreate(['id_pengumuman' => $pengumuman['id_pengumuman']], $pengumuman);
        }

        // Komentar (5 data)
        $komentars = [
            ['id_komentar' => 'KM01', 'id_orang_tua' => 'OT01', 'komentar' => 'Terima kasih atas informasinya'],
            ['id_komentar' => 'KM02', 'id_orang_tua' => 'OT02', 'komentar' => 'Saya akan hadir'],
            ['id_komentar' => 'KM03', 'id_orang_tua' => 'OT03', 'komentar' => 'Baik, akan dipersiapkan'],
            ['id_komentar' => 'KM04', 'id_orang_tua' => 'OT04', 'komentar' => 'Terima kasih infonya'],
            ['id_komentar' => 'KM05', 'id_orang_tua' => 'OT05', 'komentar' => 'Sangat bagus'],
        ];

        foreach ($komentars as $komentar) {
            Komentar::firstOrCreate(['id_komentar' => $komentar['id_komentar']], $komentar);
        }

        $this->command->info('✅ Demo data seeded successfully!');
        $this->command->info('📊 Summary:');
        $this->command->info('   - Admins: '.Admin::count());
        $this->command->info('   - Guru: '.Guru::count());
        $this->command->info('   - Orang Tua: '.OrangTua::count());
        $this->command->info('   - Siswa: '.Siswa::count());
        $this->command->info('   - Mata Pelajaran: '.MataPelajaran::count());
        $this->command->info('   - Jadwal: '.Jadwal::count());
        $this->command->info('   - Absensi: '.Absensi::count());
        $this->command->info('   - Laporan: '.LaporanPerkembangan::count());
        $this->command->info('   - Perilaku: '.Perilaku::count());
        $this->command->info('   - Pengumuman: '.Pengumuman::count());
        $this->command->info('   - Komentar: '.Komentar::count());
    }
}
