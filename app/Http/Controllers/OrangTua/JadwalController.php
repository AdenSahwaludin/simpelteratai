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

        // Get all children of the logged-in parent with their jadwal
        $siswaList = Siswa::where('id_orang_tua', $orangTua->id_orang_tua)
            ->with(['jadwal.mataPelajaran', 'jadwal.guru'])
            ->get();

        // Get jadwal for all children grouped by siswa id
        $jadwalBySiswa = [];
        foreach ($siswaList as $siswa) {
            $jadwalBySiswa[$siswa->id_siswa] = $siswa->jadwal
                ->sortBy(function ($jadwal) {
                    $hariOrder = [
                        'Senin' => 1,
                        'Selasa' => 2,
                        'Rabu' => 3,
                        'Kamis' => 4,
                        'Jumat' => 5,
                        'Sabtu' => 6,
                    ];

                    return [$hariOrder[$jadwal->hari] ?? 7, $jadwal->waktu_mulai];
                })
                ->groupBy('hari');
        }

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('orangtua.jadwal.index', compact('siswaList', 'jadwalBySiswa', 'hariList'));
    }

    /**
     * Display schedule for a specific child.
     */
    public function show(string $id): View
    {
        $orangTua = auth('orangtua')->user();
        $siswa = Siswa::where('id_siswa', $id)
            ->where('id_orang_tua', $orangTua->id_orang_tua)
            ->with(['jadwal.mataPelajaran', 'jadwal.guru'])
            ->firstOrFail();

        $jadwal = $siswa->jadwal
            ->sortBy(function ($jadwal) {
                $hariOrder = [
                    'Senin' => 1,
                    'Selasa' => 2,
                    'Rabu' => 3,
                    'Kamis' => 4,
                    'Jumat' => 5,
                    'Sabtu' => 6,
                ];

                return [$hariOrder[$jadwal->hari] ?? 7, $jadwal->waktu_mulai];
            })
            ->groupBy('hari');

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('orangtua.jadwal.show', compact('siswa', 'jadwal', 'hariList'));
    }
}
