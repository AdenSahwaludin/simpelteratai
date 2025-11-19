<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\LaporanPerkembangan;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InputNilaiController extends Controller
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
            ->with(['siswa', 'mataPelajaran'])
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

        $kelasList = Siswa::distinct()->pluck('kelas')->sort()->values();

        return view('guru.input-nilai.index', compact('laporan', 'mataPelajaranList', 'kelasList', 'search', 'kelas', 'mataPelajaran'));
    }

    public function create(): View
    {
        $guru = auth('guru')->user();
        $mataPelajaranList = $guru->jadwal()->with('mataPelajaran')->get()->pluck('mataPelajaran')->unique('id_mata_pelajaran');
        $siswaList = Siswa::orderBy('nama')->get();

        return view('guru.input-nilai.create', compact('mataPelajaranList', 'siswaList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'id_mata_pelajaran' => 'required|exists:mata_pelajaran,id_mata_pelajaran',
            'nilai' => 'required|integer|min:0|max:100',
            'komentar' => 'nullable|string',
        ], [
            'id_siswa.required' => 'Siswa wajib dipilih',
            'id_mata_pelajaran.required' => 'Mata pelajaran wajib dipilih',
            'nilai.required' => 'Nilai wajib diisi',
            'nilai.integer' => 'Nilai harus berupa angka',
            'nilai.min' => 'Nilai minimal 0',
            'nilai.max' => 'Nilai maksimal 100',
        ]);

        $laporan = new LaporanPerkembangan;
        $laporan->id_laporan = 'LP'.str_pad((string) (LaporanPerkembangan::count() + 1), 1, '0', STR_PAD_LEFT);
        $laporan->id_siswa = $validated['id_siswa'];
        $laporan->id_mata_pelajaran = $validated['id_mata_pelajaran'];
        $laporan->nilai = $validated['nilai'];
        $laporan->komentar = $validated['komentar'];
        $laporan->save();

        return redirect()->route('guru.input-nilai.index')->with('success', 'Nilai berhasil ditambahkan.');
    }

    public function edit(string $id): View
    {
        $guru = auth('guru')->user();
        $laporan = LaporanPerkembangan::with(['siswa', 'mataPelajaran'])->findOrFail($id);
        $mataPelajaranList = $guru->jadwal()->with('mataPelajaran')->get()->pluck('mataPelajaran')->unique('id_mata_pelajaran');
        $siswaList = Siswa::orderBy('nama')->get();

        return view('guru.input-nilai.edit', compact('laporan', 'mataPelajaranList', 'siswaList'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $laporan = LaporanPerkembangan::findOrFail($id);

        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'id_mata_pelajaran' => 'required|exists:mata_pelajaran,id_mata_pelajaran',
            'nilai' => 'required|integer|min:0|max:100',
            'komentar' => 'nullable|string',
        ], [
            'id_siswa.required' => 'Siswa wajib dipilih',
            'id_mata_pelajaran.required' => 'Mata pelajaran wajib dipilih',
            'nilai.required' => 'Nilai wajib diisi',
            'nilai.integer' => 'Nilai harus berupa angka',
            'nilai.min' => 'Nilai minimal 0',
            'nilai.max' => 'Nilai maksimal 100',
        ]);

        $laporan->update($validated);

        return redirect()->route('guru.input-nilai.index')->with('success', 'Nilai berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $laporan = LaporanPerkembangan::findOrFail($id);
        $laporan->delete();

        return redirect()->route('guru.input-nilai.index')->with('success', 'Nilai berhasil dihapus.');
    }
}
