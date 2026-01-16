<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Komentar;
use App\Models\LaporanLengkap;
use App\Models\LaporanPerkembangan;
use App\Models\MataPelajaran;
use App\Models\Pengumuman;
use App\Models\Perilaku;
use App\Models\Siswa;
use Illuminate\Database\Seeder;

class SeederDua extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // ========================================
        // 4. MATA PELAJARAN (2 data)
        // ========================================
        MataPelajaran::create([
            'id_mata_pelajaran' => 'MP0001',
            'nama_mapel' => 'Belajar Membaca',
        ]);

        MataPelajaran::create([
            'id_mata_pelajaran' => 'MP0002',
            'nama_mapel' => 'Belajar Berhitung',
        ]);

        // ========================================
        // 5. SISWA (2 data)
        // ========================================
        Siswa::create([
            'id_siswa' => 'S000001',
            'nama' => 'Edward',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Cirebon',
            'tanggal_lahir' => '2019-05-15',
            'kelas' => 'A',
            'alamat' => 'Jl. Merdeka No. 123',
            'id_orang_tua' => 'OT00001',
        ]);

        Siswa::create([
            'id_siswa' => 'S000002',
            'nama' => 'Siti Nurhaliza',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Cirebon',
            'tanggal_lahir' => '2019-08-20',
            'kelas' => 'A',
            'alamat' => 'Jl. Sudirman No. 456',
            'id_orang_tua' => 'OT00002',
        ]);

        // ========================================
        // 6. JADWAL (2 data dengan auto-generate pertemuan)
        // ========================================
        $jadwal1 = Jadwal::create([
            'id_jadwal' => 'J00001',
            'id_guru' => 'G00001',
            'id_mata_pelajaran' => 'MP0001',
            'ruang' => 'A',
            'kelas' => 'A',
            'hari' => 'Senin',
            'waktu_mulai' => '07:00:00',
            'waktu_selesai' => '08:00:00',
            'tanggal_mulai' => '2026-01-01',
        ]);
        // Daftarkan siswa kelas A ke jadwal SEBELUM generate pertemuan
        $jadwal1->siswa()->attach(['S000001']);
        // Auto-generate 14 pertemuan (akan assign siswa yang sudah terdaftar)
        $jadwal1->generatePertemuan();

        $jadwal2 = Jadwal::create([
            'id_jadwal' => 'J00002',
            'id_guru' => 'G00002',
            'id_mata_pelajaran' => 'MP0002',
            'ruang' => 'B',
            'kelas' => 'B',
            'hari' => 'Selasa',
            'waktu_mulai' => '08:00:00',
            'waktu_selesai' => '09:00:00',
            'tanggal_mulai' => '2026-01-01',
        ]);
        // Daftarkan siswa kelas B ke jadwal SEBELUM generate pertemuan
        $jadwal2->siswa()->attach(['S000002']);
        // Auto-generate 14 pertemuan (akan assign siswa yang sudah terdaftar)
        $jadwal2->generatePertemuan();

        // ========================================
        // 7. LAPORAN PERKEMBANGAN / NILAI (2 data)
        // ========================================
        // Get absensi untuk siswa S000001 di jadwal pertama (ada di pertemuan yang sudah dibuat)
        $absensi1 = \App\Models\Absensi::whereHas('pertemuan', function ($q) {
            $q->where('id_jadwal', 'J00001');
        })->where('id_siswa', 'S000001')->first();

        $absensi2 = \App\Models\Absensi::whereHas('pertemuan', function ($q) {
            $q->where('id_jadwal', 'J00002');
        })->where('id_siswa', 'S000002')->first();

        LaporanPerkembangan::create([
            'id_laporan' => 'LP0001',
            'id_siswa' => 'S000001',
            'id_guru' => 'G00001',
            'id_mata_pelajaran' => 'MP0001',
            'nilai' => 85,
            'id_absensi' => $absensi1?->id_absensi,
            'komentar' => 'Siswa menunjukkan perkembangan yang baik dalam membaca',
        ]);

        LaporanPerkembangan::create([
            'id_laporan' => 'LP0002',
            'id_siswa' => 'S000002',
            'id_guru' => 'G00002',
            'id_mata_pelajaran' => 'MP0002',
            'nilai' => 90,
            'id_absensi' => $absensi2?->id_absensi,
            'komentar' => 'Sangat aktif dalam berhitung',
        ]);

        // ========================================
        // 8. PERILAKU (2 data)
        // ========================================
        Perilaku::create([
            'id_perilaku' => 'PR0001',
            'id_siswa' => 'S000001',
            'id_guru' => 'G00001',
            'tanggal' => '2026-01-14',
            'sosial' => 4,
            'emosional' => 4,
            'disiplin' => 5,
            'catatan_perilaku' => 'Anak sangat disiplin dan mudah bergaul dengan teman',
        ]);

        Perilaku::create([
            'id_perilaku' => 'PR0002',
            'id_siswa' => 'S000002',
            'id_guru' => 'G00002',
            'tanggal' => '2026-01-14',
            'sosial' => 5,
            'emosional' => 4,
            'disiplin' => 4,
            'catatan_perilaku' => 'Sangat ramah dan suka membantu teman',
        ]);

        // ========================================
        // 9. LAPORAN LENGKAP (2 data)
        // ========================================
        $laporan1 = LaporanLengkap::create([
            'id_laporan_lengkap' => 'LL00000000001',
            'id_siswa' => 'S000001',
            'id_guru' => 'G00001',
            'periode_mulai' => '2026-01-01',
            'periode_selesai' => '2026-01-31',
            'catatan_guru' => 'Aden menunjukkan perkembangan yang sangat baik di bulan ini',
            'target_pembelajaran' => 'Meningkatkan kemampuan membaca dan berhitung',
            'pencapaian' => 'Sudah bisa membaca kata sederhana',
            'saran' => 'Terus latihan membaca di rumah',
            'dikirim_ke_ortu' => true,
            'tanggal_kirim' => now(),
        ]);

        $laporan2 = LaporanLengkap::create([
            'id_laporan_lengkap' => 'LL00000000002',
            'id_siswa' => 'S000002',
            'id_guru' => 'G00002',
            'periode_mulai' => '2026-01-01',
            'periode_selesai' => '2026-01-31',
            'catatan_guru' => 'Siti sangat aktif dan antusias dalam pembelajaran',
            'target_pembelajaran' => 'Mengembangkan kemampuan numerasi',
            'pencapaian' => 'Mampu berhitung 1-20 dengan baik',
            'saran' => 'Berikan latihan soal cerita sederhana',
            'dikirim_ke_ortu' => true,
            'tanggal_kirim' => now(),
        ]);

        // ========================================
        // 10. KOMENTAR (2 data)
        // ========================================
        Komentar::create([
            'id_komentar' => 'KM00001',
            'id_laporan_lengkap' => 'LL00000000001',
            'id_guru' => 'G00001',
            'komentar' => 'Terima kasih atas kerjasamanya dalam mendampingi anak belajar',
        ]);

        Komentar::create([
            'id_komentar' => 'KM00002',
            'id_laporan_lengkap' => 'LL00000000001',
            'id_orang_tua' => 'OT00001',
            'parent_id' => 'KM00001',
            'komentar' => 'Terima kasih Bu, kami akan terus mendampingi Aden di rumah',
        ]);

        // ========================================
        // 11. PENGUMUMAN (2 data)
        // ========================================
        Pengumuman::create([
            'id_pengumuman' => 'PG0001',
            'id_admin' => 'A01',
            'judul' => 'Libur Semester Genap',
            'isi' => 'Libur semester genap akan dimulai tanggal 15 Juni 2026 sampai 30 Juni 2026. Sekolah akan buka kembali tanggal 1 Juli 2026.',
            'tanggal' => '2026-01-10',
            'publikasi' => true,
        ]);

        Pengumuman::create([
            'id_pengumuman' => 'PG0002',
            'id_admin' => 'A01',
            'judul' => 'Pertemuan Orang Tua Siswa',
            'isi' => 'Akan diadakan pertemuan orang tua siswa pada tanggal 20 Januari 2026 pukul 09.00 WIB di aula sekolah. Mohon kehadirannya.',
            'tanggal' => '2026-01-14',
            'publikasi' => true,
        ]);

        $this->command->info('✅ Seeder berhasil dijalankan untuk semua tabel!');
        $this->command->info('📊 Total data yang dibuat:');
        $this->command->info('   - Admin: 2 data');
        $this->command->info('   - Guru: 2 data');
        $this->command->info('   - Orang Tua: 2 data');
        $this->command->info('   - Mata Pelajaran: 2 data');
        $this->command->info('   - Siswa: 2 data');
        $this->command->info('   - Jadwal: 2 data (+ 28 pertemuan auto-generated)');
        $this->command->info('   - Laporan Perkembangan/Nilai: 2 data');
        $this->command->info('   - Perilaku: 2 data');
        $this->command->info('   - Laporan Lengkap: 2 data');
        $this->command->info('   - Komentar: 2 data');
        $this->command->info('   - Pengumuman: 2 data');
    }
}
