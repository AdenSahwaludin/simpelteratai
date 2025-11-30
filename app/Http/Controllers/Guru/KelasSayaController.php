<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\Paginator;

class KelasSayaController extends Controller
{
    public function index(): View
    {
        $guru = auth('guru')->user();

        // Get distinct classes from jadwal
        $kelasData = $guru->jadwal()
            ->with(['mataPelajaran', 'siswa'])
            ->get()
            ->groupBy('ruang')
            ->map(function ($jadwals, $ruang) {
                // Get unique students from all jadwal in this class
                $siswaIds = $jadwals
                    ->pluck('siswa')
                    ->flatten()
                    ->pluck('id_siswa')
                    ->unique();

                return [
                    'ruang' => $ruang,
                    'jadwal_count' => $jadwals->count(),
                    'siswa_count' => $siswaIds->count(),
                    'mata_pelajaran' => $jadwals->pluck('mataPelajaran.nama_mapel')->unique()->values(),
                ];
            });

        return view('guru.kelas-saya.index', compact('kelasData'));
    }

    public function show(string $ruang): View
    {
        $guru = auth('guru')->user();

        // Get jadwal for this class
        $jadwal = $guru->jadwal()
            ->where('ruang', $ruang)
            ->with(['mataPelajaran', 'siswa.orangTua'])
            ->get();

        // Get unique students from all jadwal in this class (from pivot table)
        $siswaCollection = $jadwal
            ->pluck('siswa')
            ->flatten()
            ->unique('id_siswa')
            ->values();

        // Paginate the collection manually
        $perPage = 20;
        $page = Paginator::resolveCurrentPage();
        $total = $siswaCollection->count();
        $siswa = new Paginator(
            $siswaCollection->forPage($page, $perPage),
            $perPage,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
                'query' => request()->query(),
            ]
        );

        return view('guru.kelas-saya.show', compact('siswa', 'jadwal', 'ruang', 'total'));
    }
}
