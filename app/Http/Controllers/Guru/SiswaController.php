<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $kelas = $request->input('kelas');

        $siswa = Siswa::query()
            ->with(['orangTua'])
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('id_siswa', 'like', "%{$search}%");
            })
            ->when($kelas, function ($query, $kelas) {
                return $query->where('kelas', $kelas);
            })
            ->orderBy('nama')
            ->paginate(20)
            ->appends($request->query());

        // Get distinct kelas
        $kelasList = Siswa::distinct()->pluck('kelas')->sort()->values();

        return view('guru.siswa.index', compact('siswa', 'kelasList', 'search', 'kelas'));
    }

    public function show(string $id): View
    {
        $siswa = Siswa::with(['orangTua', 'laporanPerkembangan', 'perilaku', 'absensi'])
            ->findOrFail($id);

        return view('guru.siswa.show', compact('siswa'));
    }
}
