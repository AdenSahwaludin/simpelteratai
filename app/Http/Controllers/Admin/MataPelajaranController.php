<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MataPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'nama_mapel');
        $direction = $request->input('direction', 'asc');

        // Validasi sort column untuk mencegah SQL injection
        $allowedSort = ['id_mata_pelajaran', 'nama_mapel'];
        if (! in_array($sort, $allowedSort)) {
            $sort = 'nama_mapel';
        }

        $mataPelajaran = MataPelajaran::query()
            ->withCount('jadwal')
            ->when($search, function ($query, $search) {
                return $query->where('nama_mapel', 'like', "%{$search}%")
                    ->orWhere('id_mata_pelajaran', 'like', "%{$search}%");
            })
            ->orderBy($sort, $direction)
            ->paginate(15)
            ->appends($request->query());

        return view('admin.mata_pelajaran.index', compact('mataPelajaran', 'search', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.mata_pelajaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:150|unique:mata_pelajaran,nama_mapel',
        ], [
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi',
            'nama_mapel.max' => 'Nama mata pelajaran maksimal 150 karakter',
            'nama_mapel.unique' => 'Mata pelajaran sudah ada',
        ]);

        $mataPelajaran = new MataPelajaran;
        $mataPelajaran->id_mata_pelajaran = 'MP'.str_pad((string) (MataPelajaran::count() + 1), 1, '0', STR_PAD_LEFT);
        $mataPelajaran->nama_mapel = $validated['nama_mapel'];
        $mataPelajaran->save();

        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $mataPelajaran = MataPelajaran::query()
            ->where('id_mata_pelajaran', $id)
            ->withCount('jadwal', 'laporanPerkembangan')
            ->with(['jadwal.guru', 'jadwal.mataPelajaran'])
            ->firstOrFail();

        return view('admin.mata_pelajaran.show', compact('mataPelajaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);

        return view('admin.mata_pelajaran.edit', compact('mataPelajaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);

        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:150|unique:mata_pelajaran,nama_mapel,'.$id.',id_mata_pelajaran',
        ], [
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi',
            'nama_mapel.max' => 'Nama mata pelajaran maksimal 150 karakter',
            'nama_mapel.unique' => 'Mata pelajaran sudah ada',
        ]);

        $mataPelajaran->nama_mapel = $validated['nama_mapel'];
        $mataPelajaran->save();

        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);

        // Check if mata pelajaran has related jadwal
        if ($mataPelajaran->jadwal()->count() > 0) {
            return redirect()->route('admin.mata-pelajaran.index')->with('error', 'Mata pelajaran tidak dapat dihapus karena masih memiliki jadwal terkait.');
        }

        $mataPelajaran->delete();

        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
