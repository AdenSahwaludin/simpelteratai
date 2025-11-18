<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Perilaku;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CatatanPerilakuController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $kelas = $request->input('kelas');

        $perilaku = Perilaku::query()
            ->with(['siswa', 'guru'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('siswa', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                })
                    ->orWhere('catatan_perilaku', 'like', "%{$search}%");
            })
            ->when($kelas, function ($query, $kelas) {
                return $query->whereHas('siswa', function ($q) use ($kelas) {
                    $q->where('kelas', $kelas);
                });
            })
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        $kelasList = Siswa::distinct()->pluck('kelas')->sort()->values();

        return view('guru.catatan-perilaku.index', compact('perilaku', 'kelasList', 'search', 'kelas'));
    }

    public function create(): View
    {
        $siswaList = Siswa::orderBy('nama')->get();

        return view('guru.catatan-perilaku.create', compact('siswaList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $guru = auth('guru')->user();

        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'catatan_perilaku' => 'required|string',
        ], [
            'id_siswa.required' => 'Siswa wajib dipilih',
            'catatan_perilaku.required' => 'Catatan perilaku wajib diisi',
        ]);

        $perilaku = new Perilaku;
        $perilaku->id_perilaku = 'PR' . str_pad((string)(Perilaku::count() + 1), 3, '0', STR_PAD_LEFT);
        $perilaku->id_siswa = $validated['id_siswa'];
        $perilaku->id_guru = $guru->id_guru;
        $perilaku->catatan_perilaku = $validated['catatan_perilaku'];
        $perilaku->save();

        return redirect()->route('guru.catatan-perilaku.index')->with('success', 'Catatan perilaku berhasil ditambahkan.');
    }

    public function edit(string $id): View
    {
        $perilaku = Perilaku::with('siswa')->findOrFail($id);
        $siswaList = Siswa::orderBy('nama')->get();

        return view('guru.catatan-perilaku.edit', compact('perilaku', 'siswaList'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $perilaku = Perilaku::findOrFail($id);

        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'catatan_perilaku' => 'required|string',
        ], [
            'id_siswa.required' => 'Siswa wajib dipilih',
            'catatan_perilaku.required' => 'Catatan perilaku wajib diisi',
        ]);

        $perilaku->update($validated);

        return redirect()->route('guru.catatan-perilaku.index')->with('success', 'Catatan perilaku berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $perilaku = Perilaku::findOrFail($id);
        $perilaku->delete();

        return redirect()->route('guru.catatan-perilaku.index')->with('success', 'Catatan perilaku berhasil dihapus.');
    }
}
