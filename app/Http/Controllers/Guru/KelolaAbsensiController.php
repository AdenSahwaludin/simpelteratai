<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KelolaAbsensiController extends Controller
{
    public function index(Request $request): View
    {
        $guru = auth('guru')->user();
        $search = $request->input('search');
        $tanggal = $request->input('tanggal');
        $status = $request->input('status');

        $absensi = Absensi::query()
            ->with(['siswa', 'jadwal.mataPelajaran'])
            ->whereHas('jadwal', function ($query) use ($guru) {
                $query->where('id_guru', $guru->id_guru);
            })
            ->when($search, function ($query, $search) {
                return $query->whereHas('siswa', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            })
            ->when($tanggal, function ($query, $tanggal) {
                return $query->whereDate('tanggal', $tanggal);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status_kehadiran', $status);
            })
            ->latest('tanggal')
            ->paginate(20)
            ->appends($request->query());

        return view('guru.kelola-absensi.index', compact('absensi', 'search', 'tanggal', 'status'));
    }

    public function create(): View
    {
        $guru = auth('guru')->user();
        $jadwalList = $guru->jadwal()->with('mataPelajaran')->get();
        $siswaList = Siswa::orderBy('nama')->get();

        return view('guru.kelola-absensi.create', compact('jadwalList', 'siswaList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'tanggal' => 'required|date',
            'status_kehadiran' => 'required|in:hadir,izin,sakit,alpha',
        ], [
            'id_siswa.required' => 'Siswa wajib dipilih',
            'id_jadwal.required' => 'Jadwal wajib dipilih',
            'tanggal.required' => 'Tanggal wajib diisi',
            'status_kehadiran.required' => 'Status kehadiran wajib dipilih',
        ]);

        $absensi = new Absensi;
        $absensi->id_absensi = 'A' . str_pad((string)(Absensi::count() + 1), 3, '0', STR_PAD_LEFT);
        $absensi->id_siswa = $validated['id_siswa'];
        $absensi->id_jadwal = $validated['id_jadwal'];
        $absensi->tanggal = $validated['tanggal'];
        $absensi->status_kehadiran = $validated['status_kehadiran'];
        $absensi->save();

        return redirect()->route('guru.kelola-absensi.index')->with('success', 'Absensi berhasil ditambahkan.');
    }

    public function edit(string $id): View
    {
        $guru = auth('guru')->user();
        $absensi = Absensi::with(['siswa', 'jadwal'])->findOrFail($id);
        $jadwalList = $guru->jadwal()->with('mataPelajaran')->get();
        $siswaList = Siswa::orderBy('nama')->get();

        return view('guru.kelola-absensi.edit', compact('absensi', 'jadwalList', 'siswaList'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $absensi = Absensi::findOrFail($id);

        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'tanggal' => 'required|date',
            'status_kehadiran' => 'required|in:hadir,izin,sakit,alpha',
        ], [
            'id_siswa.required' => 'Siswa wajib dipilih',
            'id_jadwal.required' => 'Jadwal wajib dipilih',
            'tanggal.required' => 'Tanggal wajib diisi',
            'status_kehadiran.required' => 'Status kehadiran wajib dipilih',
        ]);

        $absensi->update($validated);

        return redirect()->route('guru.kelola-absensi.index')->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return redirect()->route('guru.kelola-absensi.index')->with('success', 'Absensi berhasil dihapus.');
    }
}
