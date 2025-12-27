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
        $sort = $request->input('sort', 'waktu_mulai');
        $direction = $request->input('direction', 'asc');

        // Validasi sort column
        $allowedSort = ['id_jadwal', 'waktu_mulai', 'ruang'];
        if (! in_array($sort, $allowedSort)) {
            $sort = 'waktu_mulai';
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
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'kelas' => 'required|string|max:20',
            'tanggal_mulai' => 'required|date',
        ], [
            'id_guru.required' => 'Guru wajib dipilih',
            'id_guru.exists' => 'Data guru tidak ditemukan',
            'id_mata_pelajaran.required' => 'Mata pelajaran wajib dipilih',
            'id_mata_pelajaran.exists' => 'Data mata pelajaran tidak ditemukan',
            'ruang.required' => 'Ruang kelas wajib diisi',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi',
            'waktu_mulai.date_format' => 'Format waktu mulai tidak valid',
            'waktu_selesai.required' => 'Waktu selesai wajib diisi',
            'waktu_selesai.date_format' => 'Format waktu selesai tidak valid',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai',
            'hari.required' => 'Hari wajib dipilih',
            'kelas.required' => 'Kelas wajib dipilih',
            'tanggal_mulai.required' => 'Tanggal mulai semester wajib diisi',
            'tanggal_mulai.date' => 'Format tanggal tidak valid',
        ]);

        $jadwal = new Jadwal;
        $jadwal->id_jadwal = 'J'.str_pad((string) (Jadwal::count() + 1), 2, '0', STR_PAD_LEFT);
        $jadwal->id_guru = $validated['id_guru'];
        $jadwal->id_mata_pelajaran = $validated['id_mata_pelajaran'];
        $jadwal->ruang = $validated['ruang'];
        $jadwal->waktu_mulai = $validated['waktu_mulai'];
        $jadwal->waktu_selesai = $validated['waktu_selesai'];
        $jadwal->hari = $validated['hari'];
        $jadwal->kelas = $validated['kelas'];
        $jadwal->tanggal_mulai = $validated['tanggal_mulai'];
        $jadwal->save();

        // Auto-generate 14 pertemuan dan assign semua siswa
        try {
            $jadwal->generatePertemuan();
            $message = 'Jadwal berhasil ditambahkan dengan 14 pertemuan dan semua siswa terdaftar.';
        } catch (\Exception $e) {
            $message = 'Jadwal berhasil ditambahkan, tapi gagal generate pertemuan: '.$e->getMessage();
        }

        return redirect()->route('admin.jadwal.index')->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $jadwal = Jadwal::query()
            ->where('id_jadwal', $id)
            ->with(['guru', 'mataPelajaran', 'siswa', 'pertemuan.absensi'])
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
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
        ], [
            'id_guru.required' => 'Guru wajib dipilih',
            'id_guru.exists' => 'Data guru tidak ditemukan',
            'id_mata_pelajaran.required' => 'Mata pelajaran wajib dipilih',
            'id_mata_pelajaran.exists' => 'Data mata pelajaran tidak ditemukan',
            'ruang.required' => 'Ruang kelas wajib diisi',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi',
            'waktu_mulai.date_format' => 'Format waktu mulai tidak valid',
            'waktu_selesai.required' => 'Waktu selesai wajib diisi',
            'waktu_selesai.date_format' => 'Format waktu selesai tidak valid',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai',
        ]);

        $jadwal->id_guru = $validated['id_guru'];
        $jadwal->id_mata_pelajaran = $validated['id_mata_pelajaran'];
        $jadwal->ruang = $validated['ruang'];
        $jadwal->waktu_mulai = $validated['waktu_mulai'];
        $jadwal->waktu_selesai = $validated['waktu_selesai'];
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
