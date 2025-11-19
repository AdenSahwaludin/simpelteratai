<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrangTua;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $kelas = $request->input('kelas');
        $sort = $request->input('sort', 'nama');
        $direction = $request->input('direction', 'asc');

        // Validasi sort column untuk mencegah SQL injection
        $allowedSort = ['id_siswa', 'nama', 'kelas'];
        if (! in_array($sort, $allowedSort)) {
            $sort = 'nama';
        }

        $siswa = Siswa::query()
            ->with('orangTua')
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('id_siswa', 'like', "%{$search}%");
            })
            ->when($kelas, function ($query, $kelas) {
                return $query->where('kelas', $kelas);
            })
            ->orderBy($sort, $direction)
            ->paginate(20)
            ->appends($request->query());

        $kelasList = Siswa::query()->distinct()->pluck('kelas')->sort();

        return view('admin.siswa.index', compact('siswa', 'kelasList', 'search', 'kelas', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $orangTuaList = OrangTua::query()->orderBy('nama')->get();

        return view('admin.siswa.create', compact('orangTuaList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:50',
            'tanggal_lahir' => 'nullable|date',
            'kelas' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'id_orang_tua' => 'required|exists:orang_tua,id_orang_tua',
        ], [
            'nama.required' => 'Nama siswa wajib diisi',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'kelas.required' => 'Kelas wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'id_orang_tua.required' => 'Orang tua wajib dipilih',
            'id_orang_tua.exists' => 'Data orang tua tidak ditemukan',
        ]);

        $siswa = new Siswa;
        $siswa->id_siswa = 'S'.str_pad((string) (Siswa::count() + 1), 3, '0', STR_PAD_LEFT);
        $siswa->nama = $validated['nama'];
        $siswa->jenis_kelamin = $validated['jenis_kelamin'];
        $siswa->tempat_lahir = $validated['tempat_lahir'];
        $siswa->tanggal_lahir = $validated['tanggal_lahir'];
        $siswa->kelas = $validated['kelas'];
        $siswa->alamat = $validated['alamat'];
        $siswa->id_orang_tua = $validated['id_orang_tua'];
        $siswa->save();

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $siswa = Siswa::query()
            ->where('id_siswa', $id)
            ->with(['orangTua', 'absensi', 'laporanPerkembangan.mataPelajaran', 'perilaku'])
            ->firstOrFail();

        return view('admin.siswa.show', compact('siswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $siswa = Siswa::findOrFail($id);
        $orangTuaList = OrangTua::query()->orderBy('nama')->get();

        return view('admin.siswa.edit', compact('siswa', 'orangTuaList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $siswa = Siswa::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:50',
            'tanggal_lahir' => 'nullable|date',
            'kelas' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'id_orang_tua' => 'required|exists:orang_tua,id_orang_tua',
        ], [
            'nama.required' => 'Nama siswa wajib diisi',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'kelas.required' => 'Kelas wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'id_orang_tua.required' => 'Orang tua wajib dipilih',
            'id_orang_tua.exists' => 'Data orang tua tidak ditemukan',
        ]);

        $siswa->nama = $validated['nama'];
        $siswa->jenis_kelamin = $validated['jenis_kelamin'];
        $siswa->tempat_lahir = $validated['tempat_lahir'];
        $siswa->tanggal_lahir = $validated['tanggal_lahir'];
        $siswa->kelas = $validated['kelas'];
        $siswa->alamat = $validated['alamat'];
        $siswa->id_orang_tua = $validated['id_orang_tua'];
        $siswa->save();

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
