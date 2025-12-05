<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;

class JadwalController extends Controller
{
    /**
     * Display a listing of the child's schedule grouped by day.
     */
    public function index(): View
    {
        $orangTua = auth('orangtua')->user();

        // Get all children of the logged-in parent
        $siswaList = Siswa::where('id_orang_tua', $orangTua->id_orang_tua)->get();

        // Get jadwal for all children grouped by kelas
        $jadwalByKelas = [];
        foreach ($siswaList as $siswa) {
            if (! isset($jadwalByKelas[$siswa->kelas])) {
                $jadwal = Jadwal::where('kelas', $siswa->kelas)
                    ->with(['mataPelajaran', 'guru'])
                    ->orderByRaw("
                        CASE 
                            WHEN hari = 'Senin' THEN 1
                            WHEN hari = 'Selasa' THEN 2
                            WHEN hari = 'Rabu' THEN 3
                            WHEN hari = 'Kamis' THEN 4
                            WHEN hari = 'Jumat' THEN 5
                            WHEN hari = 'Sabtu' THEN 6
                            ELSE 7
                        END
                    ")
                    ->orderBy('waktu_mulai')
                    ->get();

                $jadwalByKelas[$siswa->kelas] = $jadwal->groupBy('hari');
            }
        }

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('orangtua.jadwal.index', compact('siswaList', 'jadwalByKelas', 'hariList'));
    }

    /**
     * Display schedule for a specific child.
     */
    public function show(string $id): View
    {
        $orangTua = auth('orangtua')->user();
        $siswa = Siswa::where('id_siswa', $id)
            ->where('id_orang_tua', $orangTua->id_orang_tua)
            ->firstOrFail();

        $jadwal = Jadwal::where('kelas', $siswa->kelas)
            ->with(['mataPelajaran', 'guru'])
            ->orderByRaw("
                CASE 
                    WHEN hari = 'Senin' THEN 1
                    WHEN hari = 'Selasa' THEN 2
                    WHEN hari = 'Rabu' THEN 3
                    WHEN hari = 'Kamis' THEN 4
                    WHEN hari = 'Jumat' THEN 5
                    WHEN hari = 'Sabtu' THEN 6
                    ELSE 7
                END
            ")
            ->orderBy('waktu_mulai')
            ->get()
            ->groupBy('hari');

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('orangtua.jadwal.show', compact('siswa', 'jadwal', 'hariList'));
    }
}
