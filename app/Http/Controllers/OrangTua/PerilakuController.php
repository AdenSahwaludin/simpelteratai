<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Perilaku;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerilakuController extends Controller
{
    /**
     * Display a listing of the behavior reports.
     */
    public function index(Request $request): View
    {
        $orangTua = auth('orangtua')->user();
        $search = $request->input('search');
        $anakId = $request->input('anak_id');

        $anakList = Siswa::where('id_orang_tua', $orangTua->id_orang_tua)
            ->orderBy('nama')
            ->get();

        $perilaku = Perilaku::query()
            ->with('siswa')
            ->whereHas('siswa', function ($query) use ($orangTua) {
                $query->where('id_orang_tua', $orangTua->id_orang_tua);
            })
            ->when($anakId, function ($query, $anakId) {
                return $query->where('id_siswa', $anakId);
            })
            ->when($search, function ($query, $search) {
                return $query->where('catatan_perilaku', 'like', "%{$search}%")
                    ->orWhereHas('siswa', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        return view('orangtua.perilaku.index', compact('perilaku', 'anakList', 'search', 'anakId'));
    }

    /**
     * Display the specified behavior report.
     */
    public function show(string $id): View
    {
        $orangTua = auth('orangtua')->user();

        $perilaku = Perilaku::query()
            ->where('id_perilaku', $id)
            ->with('siswa')
            ->whereHas('siswa', function ($query) use ($orangTua) {
                $query->where('id_orang_tua', $orangTua->id_orang_tua);
            })
            ->firstOrFail();

        return view('orangtua.perilaku.show', compact('perilaku'));
    }
}
