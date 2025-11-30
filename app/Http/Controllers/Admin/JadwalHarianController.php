<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalHarian;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class JadwalHarianController extends Controller
{
    public function index(Request $request): View
    {
        $tanggal = $request->input('tanggal');
        $kelas = $request->input('kelas');

        $jadwal = JadwalHarian::query()
            ->when($tanggal, function ($query, $tanggal) {
                return $query->whereDate('tanggal', $tanggal);
            })
            ->when($kelas, function ($query, $kelas) {
                return $query->where('kelas', $kelas);
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu_mulai', 'asc')
            ->paginate(20)
            ->appends($request->query());

        $kelasList = JadwalHarian::distinct()->pluck('kelas')->filter()->sort()->values();

        return view('admin.jadwal-harian.index', compact('jadwal', 'kelasList', 'tanggal', 'kelas'));
    }

    public function create(): View
    {
        return view('admin.jadwal-harian.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'tema' => 'nullable|string|max:255',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'kegiatan' => 'required|string',
            'catatan' => 'nullable|string',
            'kelas' => 'nullable|string|max:50',
        ], [
            'tanggal.required' => 'Tanggal wajib diisi',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi',
            'waktu_selesai.required' => 'Waktu selesai wajib diisi',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai',
            'kegiatan.required' => 'Kegiatan wajib diisi',
        ]);

        $jadwal = new JadwalHarian;
        $jadwal->id_jadwal_harian = 'JH'.str_pad((string) (JadwalHarian::count() + 1), 4, '0', STR_PAD_LEFT);
        $jadwal->tanggal = $validated['tanggal'];
        $jadwal->tema = $validated['tema'];
        $jadwal->waktu_mulai = $validated['waktu_mulai'];
        $jadwal->waktu_selesai = $validated['waktu_selesai'];
        $jadwal->kegiatan = $validated['kegiatan'];
        $jadwal->catatan = $validated['catatan'];
        $jadwal->kelas = $validated['kelas'];
        $jadwal->save();

        return redirect()->route('admin.jadwal-harian.index')->with('success', 'Jadwal harian berhasil ditambahkan.');
    }

    public function edit(string $id): View
    {
        $jadwal = JadwalHarian::findOrFail($id);

        return view('admin.jadwal-harian.edit', compact('jadwal'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $jadwal = JadwalHarian::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'tema' => 'nullable|string|max:255',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'kegiatan' => 'required|string',
            'catatan' => 'nullable|string',
            'kelas' => 'nullable|string|max:50',
        ], [
            'tanggal.required' => 'Tanggal wajib diisi',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi',
            'waktu_selesai.required' => 'Waktu selesai wajib diisi',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai',
            'kegiatan.required' => 'Kegiatan wajib diisi',
        ]);

        $jadwal->update($validated);

        return redirect()->route('admin.jadwal-harian.index')->with('success', 'Jadwal harian berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $jadwal = JadwalHarian::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('admin.jadwal-harian.index')->with('success', 'Jadwal harian berhasil dihapus.');
    }
}
