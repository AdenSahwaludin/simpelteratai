<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request): View
    {
        $guru = auth('guru')->user();
        $search = $request->input('search');
        $ruang = $request->input('ruang');

        $jadwal = $guru->jadwal()
            ->with(['mataPelajaran'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('mataPelajaran', function ($q) use ($search) {
                    $q->where('nama_mapel', 'like', "%{$search}%");
                })
                    ->orWhere('ruang', 'like', "%{$search}%");
            })
            ->when($ruang, function ($query, $ruang) {
                return $query->where('ruang', $ruang);
            })
            ->orderBy('waktu')
            ->paginate(20)
            ->appends($request->query());

        // Get distinct ruang
        $ruangList = $guru->jadwal()->distinct()->pluck('ruang')->sort()->values();

        return view('guru.jadwal.index', compact('jadwal', 'ruangList', 'search', 'ruang'));
    }
}
