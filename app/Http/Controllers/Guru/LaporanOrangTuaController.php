<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\LaporanPerkembangan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LaporanOrangTuaController extends Controller
{
    public function index(Request $request): View
    {
        $guru = auth('guru')->user();
        $search = $request->input('search');
        $kelas = $request->input('kelas');
        $mataPelajaran = $request->input('mata_pelajaran');

        // Get mata pelajaran taught by this guru
        $mataPelajaranList = $guru->jadwal()->with('mataPelajaran')->get()->pluck('mataPelajaran')->unique('id_mata_pelajaran');

        $laporan = LaporanPerkembangan::query()
            ->with(['siswa.orangTua', 'mataPelajaran'])
            ->whereHas('mataPelajaran.jadwal', function ($query) use ($guru) {
                $query->where('id_guru', $guru->id_guru);
            })
            ->when($search, function ($query, $search) {
                return $query->whereHas('siswa', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            })
            ->when($kelas, function ($query, $kelas) {
                return $query->whereHas('siswa', function ($q) use ($kelas) {
                    $q->where('kelas', $kelas);
                });
            })
            ->when($mataPelajaran, function ($query, $mataPelajaran) {
                return $query->where('id_mata_pelajaran', $mataPelajaran);
            })
            ->latest()
            ->paginate(20)
            ->appends($request->query());

        $kelasList = $guru->jadwal()->distinct('ruang')->pluck('ruang')->sort()->values();

        return view('guru.laporan-orangtua.index', compact('laporan', 'mataPelajaranList', 'kelasList', 'search', 'kelas', 'mataPelajaran'));
    }

    public function show(string $id): View
    {
        $laporan = LaporanPerkembangan::with(['siswa.orangTua', 'mataPelajaran'])->findOrFail($id);

        return view('guru.laporan-orangtua.show', compact('laporan'));
    }
}
