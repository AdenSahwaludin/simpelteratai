<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\LaporanLengkap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LaporanLengkapController extends Controller
{
    public function index(Request $request): View
    {
        $orangTua = Auth::guard('orangtua')->user();
        $anakIds = $orangTua->siswa->pluck('id_siswa');
        $search = $request->input('search');

        $laporan = LaporanLengkap::query()
            ->whereIn('id_siswa', $anakIds)
            ->where('dikirim_ke_ortu', true)
            ->with(['siswa', 'guru'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('siswa', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            })
            ->orderBy('tanggal_kirim', 'desc')
            ->paginate(15)
            ->appends($request->query());

        return view('orangtua.laporan-lengkap.index', compact('laporan', 'search'));
    }

    public function show(string $id): View
    {
        $orangTua = Auth::guard('orangtua')->user();
        $anakIds = $orangTua->siswa->pluck('id_siswa');

        $laporan = LaporanLengkap::where('id_laporan_lengkap', $id)
            ->whereIn('id_siswa', $anakIds)
            ->where('dikirim_ke_ortu', true)
            ->with(['siswa', 'guru'])
            ->firstOrFail();

        $kehadiran = $laporan->getKehadiranData();
        $nilai = $laporan->getNilaiData();
        $perilaku = $laporan->getPerilakuData();

        return view('orangtua.laporan-lengkap.show', compact('laporan', 'kehadiran', 'nilai', 'perilaku'));
    }
}
