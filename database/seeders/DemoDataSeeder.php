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
            ['id_guru' => 'G01', 'nama' => 'Budi Santoso', 'email' => 'budi@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '082134567801'],
            ['id_guru' => 'G02', 'nama' => 'Siti Aminah', 'email' => 'siti@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '082134567802'],
            ['id_guru' => 'G03', 'nama' => 'Ahmad Dahlan', 'email' => 'ahmad@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '082134567803'],
            ['id_guru' => 'G04', 'nama' => 'Dewi Sartika', 'email' => 'dewi@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '082134567804'],
            ['id_guru' => 'G05', 'nama' => 'Kartini Wijaya', 'email' => 'kartini@simpel.com', 'password' => Hash::make('password'), 'no_telpon' => '082134567805'],
        ];

        foreach ($gurus as $guru) {
            Guru::firstOrCreate(['id_guru' => $guru['id_guru']], $guru);
        }

        // Orang Tua (5 data)
        $orangTuas = [
            ['id_orang_tua' => 'OT01', 'nama' => 'Suparman', 'email' => 'suparman@gmail.com', 'password' => Hash::make('password'), 'no_telpon' => '083234567801'],
            ['id_orang_tua' => 'OT02', 'nama' => 'Supardi', 'email' => 'supardi@gmail.com', 'password' => Hash::make('password'), 'no_telpon' => '083234567802'],
            ['id_orang_tua' => 'OT03', 'nama' => 'Sumanto', 'email' => 'sumanto@gmail.com', 'password' => Hash::make('password'), 'no_telpon' => '083234567803'],
            ['id_orang_tua' => 'OT04', 'nama' => 'Sukirman', 'email' => 'sukirman@gmail.com', 'password' => Hash::make('password'), 'no_telpon' => '083234567804'],
            ['id_orang_tua' => 'OT05', 'nama' => 'Sutarjo', 'email' => 'sutarjo@gmail.com', 'password' => Hash::make('password'), 'no_telpon' => '083234567805'],
        ];

        foreach ($orangTuas as $orangTua) {
            OrangTua::firstOrCreate(['id_orang_tua' => $orangTua['id_orang_tua']], $orangTua);
        }

        // Siswa (5 data)
        $siswaData = [
            ['id_siswa' => 'SW01', 'nama' => 'Andi Pratama', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'Bandung', 'tanggal_lahir' => '2013-05-15', 'kelas' => '5A', 'alamat' => 'Jl. Merdeka No. 10', 'id_orang_tua' => 'OT01'],
            ['id_siswa' => 'SW02', 'nama' => 'Budi Setiawan', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'Sumedang', 'tanggal_lahir' => '2013-08-22', 'kelas' => '5A', 'alamat' => 'Jl. Sudirman No. 20', 'id_orang_tua' => 'OT02'],
            ['id_siswa' => 'SW03', 'nama' => 'Citra Dewi', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'Cirebon', 'tanggal_lahir' => '2013-03-10', 'kelas' => '5B', 'alamat' => 'Jl. Gatot Subroto No. 30', 'id_orang_tua' => 'OT03'],
            ['id_siswa' => 'SW04', 'nama' => 'Doni Ramadhan', 'jenis_kelamin' => 'L', 'tempat_lahir' => 'Bandung', 'tanggal_lahir' => '2013-11-30', 'kelas' => '5B', 'alamat' => 'Jl. Ahmad Yani No. 40', 'id_orang_tua' => 'OT04'],
            ['id_siswa' => 'SW05', 'nama' => 'Eka Putri', 'jenis_kelamin' => 'P', 'tempat_lahir' => 'Jakarta', 'tanggal_lahir' => '2012-07-18', 'kelas' => '6A', 'alamat' => 'Jl. Diponegoro No. 50', 'id_orang_tua' => 'OT05'],
        ];

        foreach ($siswaData as $siswa) {
            Siswa::firstOrCreate(['id_siswa' => $siswa['id_siswa']], $siswa);
        }

        // Mata Pelajaran (5 data)
        $mataPelajarans = [
            ['id_mata_pelajaran' => 'MP1', 'nama_mapel' => 'Matematika'],
            ['id_mata_pelajaran' => 'MP2', 'nama_mapel' => 'Bahasa Indonesia'],
            ['id_mata_pelajaran' => 'MP3', 'nama_mapel' => 'IPA'],
            ['id_mata_pelajaran' => 'MP4', 'nama_mapel' => 'IPS'],
            ['id_mata_pelajaran' => 'MP5', 'nama_mapel' => 'Bahasa Inggris'],
        ];

        foreach ($mataPelajarans as $mapel) {
            MataPelajaran::firstOrCreate(['id_mata_pelajaran' => $mapel['id_mata_pelajaran']], $mapel);
        }

        // Jadwal (5 data)
        $jadwals = [
            ['id_jadwal' => 'JD1', 'id_guru' => 'G01', 'id_mata_pelajaran' => 'MP1', 'waktu' => '07:00:00', 'ruang' => '5A'],
            ['id_jadwal' => 'JD2', 'id_guru' => 'G02', 'id_mata_pelajaran' => 'MP2', 'waktu' => '08:30:00', 'ruang' => '5A'],
            ['id_jadwal' => 'JD3', 'id_guru' => 'G03', 'id_mata_pelajaran' => 'MP3', 'waktu' => '10:00:00', 'ruang' => '5B'],
            ['id_jadwal' => 'JD4', 'id_guru' => 'G04', 'id_mata_pelajaran' => 'MP4', 'waktu' => '13:00:00', 'ruang' => '5B'],
            ['id_jadwal' => 'JD5', 'id_guru' => 'G05', 'id_mata_pelajaran' => 'MP5', 'waktu' => '07:00:00', 'ruang' => '6A'],
        ];

        foreach ($jadwals as $jadwal) {
            Jadwal::firstOrCreate(['id_jadwal' => $jadwal['id_jadwal']], $jadwal);
        }

        // Absensi (5 data)
        $absensis = [
            ['id_absensi' => 'A001', 'id_siswa' => 'SW01', 'id_jadwal' => 'JD1', 'tanggal' => '2025-11-18', 'status_kehadiran' => 'hadir'],
            ['id_absensi' => 'A002', 'id_siswa' => 'SW02', 'id_jadwal' => 'JD2', 'tanggal' => '2025-11-18', 'status_kehadiran' => 'hadir'],
            ['id_absensi' => 'A003', 'id_siswa' => 'SW03', 'id_jadwal' => 'JD3', 'tanggal' => '2025-11-18', 'status_kehadiran' => 'izin'],
            ['id_absensi' => 'A004', 'id_siswa' => 'SW04', 'id_jadwal' => 'JD4', 'tanggal' => '2025-11-18', 'status_kehadiran' => 'sakit'],
            ['id_absensi' => 'A005', 'id_siswa' => 'SW05', 'id_jadwal' => 'JD5', 'tanggal' => '2025-11-18', 'status_kehadiran' => 'alpha'],
        ];

        foreach ($absensis as $absensi) {
            Absensi::firstOrCreate(['id_absensi' => $absensi['id_absensi']], $absensi);
        }

        // Laporan Perkembangan (5 data)
        $laporans = [
            ['id_laporan' => 'LP1', 'id_siswa' => 'SW01', 'id_mata_pelajaran' => 'MP1', 'nilai' => 85, 'id_absensi' => 'A001', 'komentar' => 'Sangat baik'],
            ['id_laporan' => 'LP2', 'id_siswa' => 'SW02', 'id_mata_pelajaran' => 'MP2', 'nilai' => 90, 'id_absensi' => 'A002', 'komentar' => 'Excellent'],
            ['id_laporan' => 'LP3', 'id_siswa' => 'SW03', 'id_mata_pelajaran' => 'MP3', 'nilai' => 78, 'id_absensi' => 'A003', 'komentar' => 'Baik, perlu ditingkatkan'],
            ['id_laporan' => 'LP4', 'id_siswa' => 'SW04', 'id_mata_pelajaran' => 'MP4', 'nilai' => 88, 'id_absensi' => 'A004', 'komentar' => 'Sangat memuaskan'],
            ['id_laporan' => 'LP5', 'id_siswa' => 'SW05', 'id_mata_pelajaran' => 'MP5', 'nilai' => 92, 'id_absensi' => 'A005', 'komentar' => 'Luar biasa'],
        ];

        foreach ($laporans as $laporan) {
            LaporanPerkembangan::firstOrCreate(['id_laporan' => $laporan['id_laporan']], $laporan);
        }

        // Perilaku (5 data)
        $perilakus = [
            ['id_perilaku' => 'PR1', 'id_siswa' => 'SW01', 'catatan_perilaku' => 'Siswa aktif bertanya di kelas'],
            ['id_perilaku' => 'PR2', 'id_siswa' => 'SW02', 'catatan_perilaku' => 'Rajin mengerjakan tugas'],
            ['id_perilaku' => 'PR3', 'id_siswa' => 'SW03', 'catatan_perilaku' => 'Sopan dan santun'],
            ['id_perilaku' => 'PR4', 'id_siswa' => 'SW04', 'catatan_perilaku' => 'Membantu teman yang kesulitan'],
            ['id_perilaku' => 'PR5', 'id_siswa' => 'SW05', 'catatan_perilaku' => 'Disiplin dan tepat waktu'],
        ];

        foreach ($perilakus as $perilaku) {
            Perilaku::firstOrCreate(['id_perilaku' => $perilaku['id_perilaku']], $perilaku);
        }

        // Pengumuman (5 data)
        $pengumumans = [
            ['id_pengumuman' => 'PG1', 'judul' => 'Libur Semester Ganjil', 'isi' => 'Libur semester ganjil akan dimulai tanggal 20 Desember 2025', 'tanggal' => '2025-11-18', 'id_admin' => 'A01'],
            ['id_pengumuman' => 'PG2', 'judul' => 'Rapat Orang Tua', 'isi' => 'Rapat orang tua akan diadakan pada tanggal 25 November 2025 pukul 09.00 WIB', 'tanggal' => '2025-11-18', 'id_admin' => 'A02'],
            ['id_pengumuman' => 'PG3', 'judul' => 'Ujian Akhir Semester', 'isi' => 'Ujian akhir semester akan dilaksanakan tanggal 1-10 Desember 2025', 'tanggal' => '2025-11-18', 'id_admin' => 'A03'],
            ['id_pengumuman' => 'PG4', 'judul' => 'Penerimaan Siswa Baru', 'isi' => 'Pendaftaran siswa baru tahun ajaran 2026/2027 dibuka mulai 1 Januari 2026', 'tanggal' => '2025-11-18', 'id_admin' => 'A04'],
            ['id_pengumuman' => 'PG5', 'judul' => 'Kegiatan Ekstrakurikuler', 'isi' => 'Pendaftaran ekstrakurikuler dibuka untuk semester genap', 'tanggal' => '2025-11-18', 'id_admin' => 'A05'],
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
        $this->command->info('   - Admins: ' . Admin::count());
        $this->command->info('   - Guru: ' . Guru::count());
        $this->command->info('   - Orang Tua: ' . OrangTua::count());
        $this->command->info('   - Siswa: ' . Siswa::count());
        $this->command->info('   - Mata Pelajaran: ' . MataPelajaran::count());
        $this->command->info('   - Jadwal: ' . Jadwal::count());
        $this->command->info('   - Absensi: ' . Absensi::count());
        $this->command->info('   - Laporan: ' . LaporanPerkembangan::count());
        $this->command->info('   - Perilaku: ' . Perilaku::count());
        $this->command->info('   - Pengumuman: ' . Pengumuman::count());
        $this->command->info('   - Komentar: ' . Komentar::count());
    }
}
