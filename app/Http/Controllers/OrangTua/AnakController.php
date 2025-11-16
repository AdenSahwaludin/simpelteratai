<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\View\View;

class AnakController extends Controller
{
    /**
     * Display a listing of the children.
     */
    public function index(): View
    {
        $orangTua = auth('orangtua')->user();

        $anak = Siswa::where('id_orang_tua', $orangTua->id_orang_tua)
            ->with(['absensi', 'laporanPerkembangan', 'perilaku'])
            ->get();

        return view('orangtua.anak.index', compact('anak'));
    }

    /**
     * Display the specified child.
     */
    public function show(string $id): View
    {
        $orangTua = auth('orangtua')->user();

        $anak = Siswa::where('id_siswa', $id)
            ->where('id_orang_tua', $orangTua->id_orang_tua)
            ->with(['orangTua', 'absensi', 'laporanPerkembangan.mataPelajaran', 'perilaku'])
            ->firstOrFail();

        return view('orangtua.anak.show', compact('anak'));
    }
}
