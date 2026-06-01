<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request): View
    {
        $guru = auth('guru')->user();
        $search = $request->input('search');
        $kelas = $request->input('id_kelas');
        $status = $request->input('status', 'Aktif');

        $siswa = Siswa::query()
            ->when($status !== 'Semua', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->with(['orangTua', 'kelas'])
            ->whereHas('kelas', function ($query) use ($guru) {
                $query->where('id_guru_wali', $guru->id_guru);
            })
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('id_siswa', 'like', "%{$search}%");
            })
            ->when($kelas, function ($query, $kelas) {
                return $query->where('id_kelas', $kelas);
            })
            ->orderBy('nama')
            ->paginate(20)
            ->appends($request->query());

        // Get distinct kelas where guru is wali kelas
        $kelasList = Kelas::where('id_guru_wali', $guru->id_guru)->get();

        return view('guru.siswa.index', compact('siswa', 'kelasList', 'search', 'kelas', 'status'));
    }

    public function show(string $id): View
    {
        $siswa = Siswa::with(['orangTua', 'laporanPerkembangan', 'perilaku', 'absensi'])
            ->findOrFail($id);

        return view('guru.siswa.show', compact('siswa'));
    }

    /**
     * Show graduation form (bulk update status to Alumni).
     */
    public function showGraduation(Request $request): View
    {
        $guru = auth('guru')->user();
        $sourceKelasId = $request->input('source_kelas');

        $siswaList = collect();
        if ($sourceKelasId) {
            $siswaList = Siswa::query()
                ->where('id_kelas', $sourceKelasId)
                ->where('status', 'Aktif')
                ->with(['orangTua', 'kelas'])
                ->orderBy('nama')
                ->get();
        }

        $kelasList = Kelas::where('id_guru_wali', $guru->id_guru)->get();

        return view('guru.siswa.graduation', compact('siswaList', 'kelasList', 'sourceKelasId'));
    }

    /**
     * Process graduation (bulk update status to Alumni).
     */
    public function processGraduation(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'siswa_ids' => 'required|array|min:1',
            'siswa_ids.*' => 'exists:siswa,id_siswa',
        ], [
            'siswa_ids.required' => 'Pilih minimal 1 siswa untuk diproses',
            'siswa_ids.min' => 'Pilih minimal 1 siswa untuk diproses',
        ]);

        $updatedCount = Siswa::whereIn('id_siswa', $validated['siswa_ids'])
            ->update([
                'status' => 'Alumni',
                'keterangan_status' => 'Lulus',
                'id_kelas' => null
            ]);

        return redirect()
            ->route('guru.siswa.graduation')
            ->with('success', "Berhasil memproses {$updatedCount} siswa menjadi Alumni.");
    }
}
