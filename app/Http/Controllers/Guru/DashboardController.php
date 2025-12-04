<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\LaporanPerkembangan;
use App\Models\Pengumuman;
use App\Models\Perilaku;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $guru = auth('guru')->user();

        /** @var \App\Models\Guru $guru */
        $totalKelas = $guru->jadwal()->distinct('ruang')->count('ruang');

        // Total students in all classes
        $totalSiswa = Siswa::count();

        // Total jadwal mengajar
        $totalJadwal = $guru->jadwal()->count();

        // Attendance statistics for today
        $today = now()->format('Y-m-d');
        $todayAbsensi = Absensi::whereHas('pertemuan', function ($query) use ($today, $guru) {
            $query->whereDate('tanggal', $today)
                ->whereHas('jadwal', function ($q) use ($guru) {
                    $q->where('id_guru', $guru->id_guru);
                });
        })
            ->get();

        $hadirCount = $todayAbsensi->where('status_kehadiran', 'hadir')->count();
        $izinCount = $todayAbsensi->where('status_kehadiran', 'izin')->count();
        $sakitCount = $todayAbsensi->where('status_kehadiran', 'sakit')->count();
        $alphaCount = $todayAbsensi->where('status_kehadiran', 'alpha')->count();

        // Total laporan perkembangan created by this guru
        $totalLaporan = LaporanPerkembangan::whereHas('mataPelajaran.jadwal', function ($query) use ($guru) {
            $query->where('id_guru', $guru->id_guru);
        })->count();

        // Total behavior notes
        $totalPerilaku = Perilaku::count();

        // Recent announcements (last 5)
        $pengumumanTerbaru = Pengumuman::where('publikasi', true)
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        // Recent jadwal (upcoming)
        $jadwalTerbaru = $guru->jadwal()
            ->with(['mataPelajaran'])
            ->orderBy('waktu', 'asc')
            ->limit(5)
            ->get();

        return view('dashboards.guru', compact(
            'totalKelas',
            'totalSiswa',
            'totalJadwal',
            'hadirCount',
            'izinCount',
            'sakitCount',
            'alphaCount',
            'totalLaporan',
            'totalPerilaku',
            'pengumumanTerbaru',
            'jadwalTerbaru'
        ));
    }
}
