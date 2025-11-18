<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;

class KelasSayaController extends Controller
{
    public function index(): View
    {
        $guru = auth('guru')->user();

        // Get distinct classes from jadwal
        $kelasData = $guru->jadwal()
            ->with(['mataPelajaran'])
            ->get()
            ->groupBy('ruang')
            ->map(function ($jadwals, $ruang) {
                // Get students count in this class
                $siswaCount = Siswa::where('kelas', $ruang)->count();

                return [
                    'ruang' => $ruang,
                    'jadwal_count' => $jadwals->count(),
                    'siswa_count' => $siswaCount,
                    'mata_pelajaran' => $jadwals->pluck('mataPelajaran.nama_mapel')->unique()->values(),
                ];
            });

        return view('guru.kelas-saya.index', compact('kelasData'));
    }

    public function show(string $ruang): View
    {
        $guru = auth('guru')->user();

        // Get siswa in this class
        $siswa = Siswa::where('kelas', $ruang)
            ->with(['orangTua'])
            ->paginate(20);

        // Get jadwal for this class
        $jadwal = $guru->jadwal()
            ->where('ruang', $ruang)
            ->with(['mataPelajaran'])
            ->get();

        return view('guru.kelas-saya.show', compact('siswa', 'jadwal', 'ruang'));
    }
}
