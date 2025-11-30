<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\MataPelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $guru = $request->input('guru');
        $sort = $request->input('sort', 'waktu');
        $direction = $request->input('direction', 'asc');

        // Validasi sort column
        $allowedSort = ['id_jadwal', 'waktu', 'ruang'];
        if (! in_array($sort, $allowedSort)) {
            $sort = 'waktu';
        }

        $jadwal = Jadwal::query()
            ->with(['guru', 'mataPelajaran'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('guru', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                })
                    ->orWhereHas('mataPelajaran', function ($q) use ($search) {
                        $q->where('nama_mapel', 'like', "%{$search}%");
                    })
                    ->orWhere('ruang', 'like', "%{$search}%");
            })
            ->when($guru, function ($query, $guru) {
                return $query->where('id_guru', $guru);
            })
            ->orderBy($sort, $direction)
            ->paginate(20)
            ->appends($request->query());

        $guruList = Guru::query()->orderBy('nama')->get();

        return view('admin.jadwal.index', compact('jadwal', 'guruList', 'search', 'guru', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $guruList = Guru::query()->orderBy('nama')->get();
        $mataPelajaranList = MataPelajaran::query()->orderBy('nama_mapel')->get();

        return view('admin.jadwal.create', compact('guruList', 'mataPelajaranList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_guru' => 'required|exists:guru,id_guru',
            'id_mata_pelajaran' => 'required|exists:mata_pelajaran,id_mata_pelajaran',
            'ruang' => 'required|string|max:255',
            'waktu' => 'required|date_format:H:i',
        ], [
            'id_guru.required' => 'Guru wajib dipilih',
            'id_guru.exists' => 'Data guru tidak ditemukan',
            'id_mata_pelajaran.required' => 'Mata pelajaran wajib dipilih',
            'id_mata_pelajaran.exists' => 'Data mata pelajaran tidak ditemukan',
            'ruang.required' => 'Ruang kelas wajib diisi',
            'waktu.required' => 'Waktu wajib diisi',
            'waktu.date_format' => 'Format waktu tidak valid',
        ]);

        $jadwal = new Jadwal;
        $jadwal->id_jadwal = 'J'.str_pad((string) (Jadwal::count() + 1), 2, '0', STR_PAD_LEFT);
        $jadwal->id_guru = $validated['id_guru'];
        $jadwal->id_mata_pelajaran = $validated['id_mata_pelajaran'];
        $jadwal->ruang = $validated['ruang'];
        $jadwal->waktu = $validated['waktu'];
        $jadwal->save();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $jadwal = Jadwal::query()
            ->where('id_jadwal', $id)
            ->with(['guru', 'mataPelajaran', 'siswa', 'absensi'])
            ->firstOrFail();

        return view('admin.jadwal.show', compact('jadwal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $jadwal = Jadwal::findOrFail($id);
        $guruList = Guru::query()->orderBy('nama')->get();
        $mataPelajaranList = MataPelajaran::query()->orderBy('nama_mapel')->get();

        return view('admin.jadwal.edit', compact('jadwal', 'guruList', 'mataPelajaranList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $jadwal = Jadwal::findOrFail($id);

        $validated = $request->validate([
            'id_guru' => 'required|exists:guru,id_guru',
            'id_mata_pelajaran' => 'required|exists:mata_pelajaran,id_mata_pelajaran',
            'ruang' => 'required|string|max:255',
            'waktu' => 'required|date_format:H:i',
        ], [
            'id_guru.required' => 'Guru wajib dipilih',
            'id_guru.exists' => 'Data guru tidak ditemukan',
            'id_mata_pelajaran.required' => 'Mata pelajaran wajib dipilih',
            'id_mata_pelajaran.exists' => 'Data mata pelajaran tidak ditemukan',
            'ruang.required' => 'Ruang kelas wajib diisi',
            'waktu.required' => 'Waktu wajib diisi',
            'waktu.date_format' => 'Format waktu tidak valid',
        ]);

        $jadwal->id_guru = $validated['id_guru'];
        $jadwal->id_mata_pelajaran = $validated['id_mata_pelajaran'];
        $jadwal->ruang = $validated['ruang'];
        $jadwal->waktu = $validated['waktu'];
        $jadwal->save();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
