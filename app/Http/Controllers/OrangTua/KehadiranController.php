<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KehadiranController extends Controller
{
    /**
     * Display a listing of the attendance records.
     */
    public function index(Request $request): View
    {
        $orangTua = auth('orangtua')->user();
        $search = $request->input('search');
        $anakId = $request->input('anak_id');
        $status = $request->input('status');

        $anakList = Siswa::with('kelas')
            ->where('id_orang_tua', $orangTua->id_orang_tua)
            ->orderBy('nama')
            ->get();

        $kehadiran = Absensi::query()
            ->with(['siswa', 'pertemuan.jadwal.guru', 'pertemuan.jadwal.mataPelajaran'])
            ->whereHas('siswa', function ($query) use ($orangTua) {
                $query->where('id_orang_tua', $orangTua->id_orang_tua);
            })
            ->whereHas('pertemuan', function ($query) {
                $query->whereDate('tanggal', '<=', today());
            })
            ->when($anakId, function ($query, $anakId) {
                return $query->where('id_siswa', $anakId);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status_kehadiran', $status);
            })
            ->when($search, function ($query, $search) {
                return $query->whereHas('siswa', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            })
            ->join('pertemuan', 'absensi.id_pertemuan', '=', 'pertemuan.id_pertemuan')
            ->orderBy('pertemuan.tanggal', 'desc')
            ->select('absensi.*')
            ->paginate(20)
            ->appends($request->query());

        return view('orangtua.kehadiran.index', compact('kehadiran', 'anakList', 'search', 'anakId', 'status'));
    }

    /**
     * Display the specified attendance record.
     */
    public function show(string $id): View
    {
        $orangTua = auth('orangtua')->user();

        $kehadiran = Absensi::query()
            ->where('id_absensi', $id)
            ->with(['siswa', 'pertemuan.jadwal.guru', 'pertemuan.jadwal.mataPelajaran'])
            ->whereHas('siswa', function ($query) use ($orangTua) {
                $query->where('id_orang_tua', $orangTua->id_orang_tua);
            })
            ->firstOrFail();

        return view('orangtua.kehadiran.show', compact('kehadiran'));
    }
}
