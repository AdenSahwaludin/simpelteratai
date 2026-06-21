<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\LaporanPerkembangan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerkembanganController extends Controller
{
    /**
     * Display a listing of the development reports.
     */
    public function index(Request $request): View
    {
        $orangTua = auth('orangtua')->user();
        $mapelId = $request->input('mapel_id');
        $anakId = $request->input('anak_id');

        $anakList = Siswa::with('kelas')
            ->where('id_orang_tua', $orangTua->id_orang_tua)
            ->orderBy('nama')
            ->get();

        $mapelList = \App\Models\MataPelajaran::orderBy('nama_mapel')->get();

        $perkembangan = LaporanPerkembangan::query()
            ->with(['siswa', 'mataPelajaran', 'absensi.pertemuan'])
            ->whereHas('siswa', function ($query) use ($orangTua) {
                $query->where('id_orang_tua', $orangTua->id_orang_tua);
            })
            ->when($anakId, function ($query, $anakId) {
                return $query->where('id_siswa', $anakId);
            })
            ->when($mapelId, function ($query, $mapelId) {
                return $query->where('id_mata_pelajaran', $mapelId);
            })
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        return view('orangtua.perkembangan.index', compact('perkembangan', 'anakList', 'mapelId', 'anakId', 'mapelList'));
    }

    /**
     * Display the specified development report.
     */
    public function show(string $id): View
    {
        $orangTua = auth('orangtua')->user();

        $perkembangan = LaporanPerkembangan::query()
            ->where('id_laporan', $id)
            ->with(['siswa', 'mataPelajaran', 'absensi.pertemuan'])
            ->whereHas('siswa', function ($query) use ($orangTua) {
                $query->where('id_orang_tua', $orangTua->id_orang_tua);
            })
            ->firstOrFail();

        return view('orangtua.perkembangan.show', compact('perkembangan'));
    }
}
