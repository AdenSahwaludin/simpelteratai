<?php

namespace Database\Seeders;

use App\Models\JadwalHarian;
use Illuminate\Database\Seeder;

class JadwalHarianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tanggal = '2024-12-01'; // Senin, 1 Desember 2024
        $tema = 'Binatang';
        $kelas = 'Kelompok A1';
        $catatan = 'Anak boleh membawa boneka hewan peliharaan ke sekolahan.';

        $jadwalItems = [
            [
                'waktu_mulai' => '07:30',
                'waktu_selesai' => '08:00',
                'kegiatan' => 'Doa & Menyanyi',
            ],
            [
                'waktu_mulai' => '08:00',
                'waktu_selesai' => '08:30',
                'kegiatan' => 'Mengenal hewan peliharaan',
            ],
            [
                'waktu_mulai' => '08:30',
                'waktu_selesai' => '09:30',
                'kegiatan' => 'Bermain peran "Kucing & Kelinci"',
            ],
            [
                'waktu_mulai' => '09:30',
                'waktu_selesai' => '10:30',
                'kegiatan' => 'Istirahat',
            ],
            [
                'waktu_mulai' => '10:30',
                'waktu_selesai' => '11:00',
                'kegiatan' => 'Menggambar hewan kesukaan',
            ],
        ];

        $counter = 1;
        foreach ($jadwalItems as $item) {
            JadwalHarian::create([
                'id_jadwal_harian' => 'JH'.str_pad((string) $counter, 4, '0', STR_PAD_LEFT),
                'tanggal' => $tanggal,
                'tema' => $tema,
                'waktu_mulai' => $item['waktu_mulai'],
                'waktu_selesai' => $item['waktu_selesai'],
                'kegiatan' => $item['kegiatan'],
                'catatan' => $catatan,
                'kelas' => $kelas,
            ]);
            $counter++;
        }

        // Tambahkan jadwal untuk tanggal lain
        $tanggal2 = '2024-12-02'; // Selasa, 2 Desember 2024
        $tema2 = 'Keluarga';
        $catatan2 = 'Anak diajak membawa foto keluarga.';

        $jadwalItems2 = [
            [
                'waktu_mulai' => '07:30',
                'waktu_selesai' => '08:00',
                'kegiatan' => 'Doa & Menyanyi',
            ],
            [
                'waktu_mulai' => '08:00',
                'waktu_selesai' => '09:00',
                'kegiatan' => 'Mengenal anggota keluarga',
            ],
            [
                'waktu_mulai' => '09:00',
                'waktu_selesai' => '10:00',
                'kegiatan' => 'Menggambar keluarga',
            ],
            [
                'waktu_mulai' => '10:00',
                'waktu_selesai' => '10:30',
                'kegiatan' => 'Istirahat',
            ],
            [
                'waktu_mulai' => '10:30',
                'waktu_selesai' => '11:00',
                'kegiatan' => 'Bercerita tentang keluarga',
            ],
        ];

        foreach ($jadwalItems2 as $item) {
            JadwalHarian::create([
                'id_jadwal_harian' => 'JH'.str_pad((string) $counter, 4, '0', STR_PAD_LEFT),
                'tanggal' => $tanggal2,
                'tema' => $tema2,
                'waktu_mulai' => $item['waktu_mulai'],
                'waktu_selesai' => $item['waktu_selesai'],
                'kegiatan' => $item['kegiatan'],
                'catatan' => $catatan2,
                'kelas' => $kelas,
            ]);
            $counter++;
        }
    }
}
