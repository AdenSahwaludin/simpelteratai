<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\LaporanPerkembangan;
use App\Models\MataPelajaran;
use App\Models\OrangTua;
use App\Models\Pengumuman;
use App\Models\Siswa;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics and analytics.
     */
    public function index(): View
    {
        // Total Statistics
        $totalSiswa = Siswa::where('status', 'Aktif')->count();
        $totalGuru = Guru::count();
        $totalOrangTua = OrangTua::count();
        $totalMataPelajaran = MataPelajaran::count();

        // Kelas Statistics
        $kelasDistribution = Siswa::selectRaw('id_kelas, COUNT(*) as count')
            ->where('status', 'Aktif')
            ->groupBy('id_kelas')
            ->orderBy('id_kelas')
            ->get();

        // Recent Students
        $recentSiswa = Siswa::with('orangTua')
            ->where('status', 'Aktif')
            ->latest()
            ->limit(5)
            ->get();

        // Recent Announcements
        $recentPengumuman = Pengumuman::latest()
            ->limit(3)
            ->get();

        // Attendance Statistics (Today)
        $todayAttendance = Absensi::with('siswa')
            ->whereHas('pertemuan', function ($query) {
                $query->whereDate('tanggal', today());
            })
            ->get();

        $hadir = $todayAttendance->where('status_kehadiran', 'Hadir')->count();
        $izin = $todayAttendance->where('status_kehadiran', 'Izin')->count();
        $sakit = $todayAttendance->where('status_kehadiran', 'Sakit')->count();
        $alpha = $todayAttendance->where('status_kehadiran', 'Alpha')->count();

        // Average Score Statistics
        $averageScore = LaporanPerkembangan::whereHas('siswa', function ($query) {
            $query->where('status', 'Aktif');
        })->avg('nilai') ?? 0;

        // Top Students by Average Score
        $topStudents = Siswa::with('laporanPerkembangan')
            ->where('status', 'Aktif')
            ->get()
            ->map(function ($siswa) {
                $siswa->average_score = $siswa->laporanPerkembangan->avg('nilai') ?? 0;

                return $siswa;
            })
            ->sortByDesc('average_score')
            ->take(5);

        // Schedule for today
        $todaySchedule = Jadwal::with(['guru', 'mataPelajaran'])
            ->limit(6)
            ->get();

        return view('dashboards.admin', compact(
            'totalSiswa',
            'totalGuru',
            'totalOrangTua',
            'totalMataPelajaran',
            'kelasDistribution',
            'recentSiswa',
            'recentPengumuman',
            'hadir',
            'izin',
            'sakit',
            'alpha',
            'averageScore',
            'topStudents',
            'todaySchedule'
        ));
    }
}
