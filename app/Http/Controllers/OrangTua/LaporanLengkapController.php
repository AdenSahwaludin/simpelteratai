<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Komentar;
use App\Models\LaporanLengkap;
use Illuminate\Http\RedirectResponse;
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
            ->with(['siswa', 'guru', 'komentarList'])
            ->firstOrFail();

        $kehadiran = $laporan->getKehadiranData();
        $nilai = $laporan->getNilaiData();
        $perilaku = $laporan->getPerilakuData();

        return view('orangtua.laporan-lengkap.show', compact('laporan', 'kehadiran', 'nilai', 'perilaku'));
    }

    public function storeKomentar(Request $request, string $id): RedirectResponse
    {
        $orangTua = Auth::guard('orangtua')->user();
        $anakIds = $orangTua->siswa->pluck('id_siswa');

        $laporan = LaporanLengkap::where('id_laporan_lengkap', $id)
            ->whereIn('id_siswa', $anakIds)
            ->where('dikirim_ke_ortu', true)
            ->firstOrFail();

        $validated = $request->validate([
            'komentar' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:komentar,id_komentar',
        ], [
            'komentar.required' => 'Komentar wajib diisi',
            'komentar.max' => 'Komentar maksimal 1000 karakter',
        ]);

        // Generate ID
        $lastId = Komentar::orderByRaw('CAST(SUBSTRING(id_komentar, 2) AS UNSIGNED) DESC')
            ->limit(1)
            ->pluck('id_komentar')
            ->first();
        $nextNumber = $lastId ? (int) substr($lastId, 1) + 1 : 1;

        Komentar::create([
            'id_komentar' => 'K'.str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT),
            'id_orang_tua' => $orangTua->id_orang_tua,
            'id_laporan_lengkap' => $laporan->id_laporan_lengkap,
            'parent_id' => $validated['parent_id'] ?? null,
            'komentar' => $validated['komentar'],
        ]);

        return redirect()->route('orangtua.laporan-lengkap.show', $id)->with('success', 'Komentar berhasil ditambahkan.');
    }
}
