<?php

namespace App\Http\Controllers\OrangTua;

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
        $orangTua = auth('orangtua')->user();

        // Get all children for this parent
        $anak = Siswa::where('id_orang_tua', $orangTua->id_orang_tua)->get();
        $anakIds = $anak->pluck('id_siswa');

        // Total children count
        $totalAnak = $anak->count();

        // Total development reports
        $totalLaporan = LaporanPerkembangan::whereIn('id_siswa', $anakIds)->count();

        // Average score from development reports
        $rataRataNilai = LaporanPerkembangan::whereIn('id_siswa', $anakIds)->avg('nilai');
        $rataRataNilai = $rataRataNilai ? round($rataRataNilai, 1) : 0;

        // Attendance statistics
        $totalKehadiran = Absensi::whereIn('id_siswa', $anakIds)->count();
        $hadirCount = Absensi::whereIn('id_siswa', $anakIds)->where('status_kehadiran', 'hadir')->count();
        $izinCount = Absensi::whereIn('id_siswa', $anakIds)->where('status_kehadiran', 'izin')->count();
        $sakitCount = Absensi::whereIn('id_siswa', $anakIds)->where('status_kehadiran', 'sakit')->count();
        $alphaCount = Absensi::whereIn('id_siswa', $anakIds)->where('status_kehadiran', 'alpha')->count();

        // Attendance percentage
        $persentaseKehadiran = $totalKehadiran > 0 ? round(($hadirCount / $totalKehadiran) * 100, 1) : 0;

        // Total behavior notes
        $totalPerilaku = Perilaku::whereIn('id_siswa', $anakIds)->count();

        // Recent announcements (last 5)
        $pengumumanTerbaru = Pengumuman::orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        // Recent development reports (last 5)
        $laporanTerbaru = LaporanPerkembangan::whereIn('id_siswa', $anakIds)
            ->with(['siswa', 'mataPelajaran'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent behavior notes (last 5)
        $perilakuTerbaru = Perilaku::whereIn('id_siswa', $anakIds)
            ->with(['siswa', 'guru'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboards.orangtua', compact(
            'anak',
            'totalAnak',
            'totalLaporan',
            'rataRataNilai',
            'totalKehadiran',
            'hadirCount',
            'izinCount',
            'sakitCount',
            'alphaCount',
            'persentaseKehadiran',
            'totalPerilaku',
            'pengumumanTerbaru',
            'laporanTerbaru',
            'perilakuTerbaru'
        ));
    }
}
