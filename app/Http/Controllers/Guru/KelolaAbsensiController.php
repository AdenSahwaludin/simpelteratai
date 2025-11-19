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
        $kelas = $request->input('kelas');

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
            ->when($kelas, function ($query, $kelas) {
                return $query->whereHas('siswa', function ($q) use ($kelas) {
                    $q->where('kelas', $kelas);
                });
            })
            ->latest('tanggal')
            ->paginate(20)
            ->appends($request->query());

        // Get unique classes from guru's students
        $kelasList = Siswa::distinct('kelas')->pluck('kelas');

        return view('guru.kelola-absensi.index', compact('absensi', 'search', 'tanggal', 'kelas', 'kelasList'));
    }

    public function create(): View
    {
        $guru = auth('guru')->user();
        $jadwalList = $guru->jadwal()->with('mataPelajaran')->get();

        return view('guru.kelola-absensi.create', compact('jadwalList'));
    }

    /**
     * Load students for a specific class (AJAX endpoint)
     */
    public function loadSiswaByKelas(Request $request): \Illuminate\Http\JsonResponse
    {
        $kelas = $request->input('kelas');

        $siswa = Siswa::where('kelas', $kelas)
            ->orderBy('nama')
            ->get(['id_siswa', 'nama', 'kelas']);

        return response()->json(['siswa' => $siswa]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kelas' => 'required|string',
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*' => 'required|in:hadir,izin,sakit,alpha',
        ], [
            'kelas.required' => 'Kelas wajib dipilih',
            'id_jadwal.required' => 'Jadwal wajib dipilih',
            'tanggal.required' => 'Tanggal wajib diisi',
            'absensi.required' => 'Data absensi wajib diisi',
        ]);

        $guru = auth('guru')->user();

        // Verify that the jadwal belongs to the logged-in guru
        $jadwal = $guru->jadwal()->find($validated['id_jadwal']);
        if (!$jadwal) {
            return back()->with('error', 'Jadwal tidak ditemukan atau tidak milik Anda.');
        }

        // Get all students in the class
        $siswaList = Siswa::where('kelas', $validated['kelas'])->get();

        // Create/update absensi records for all students
        foreach ($siswaList as $siswa) {
            $status = $validated['absensi'][$siswa->id_siswa] ?? null;

            if ($status) {
                // Check if absensi already exists for this student, jadwal, and date
                $absensi = Absensi::where('id_siswa', $siswa->id_siswa)
                    ->where('id_jadwal', $validated['id_jadwal'])
                    ->where('tanggal', $validated['tanggal'])
                    ->first();

                if (!$absensi) {
                    // Generate new ID
                    $lastId = Absensi::orderByRaw('CAST(SUBSTRING(id_absensi, 2) AS UNSIGNED) DESC')->limit(1)->pluck('id_absensi')->first();
                    $nextNumber = $lastId ? (int)substr($lastId, 1) + 1 : 1;
                    $absensi = new Absensi([
                        'id_absensi' => 'A' . str_pad((string)$nextNumber, 3, '0', STR_PAD_LEFT),
                        'id_siswa' => $siswa->id_siswa,
                        'id_jadwal' => $validated['id_jadwal'],
                        'tanggal' => $validated['tanggal'],
                    ]);
                }

                $absensi->status_kehadiran = $status;
                $absensi->save();
            }
        }

        return redirect()->route('guru.kelola-absensi.index')->with('success', 'Absensi kelas berhasil disimpan.');
    }

    public function edit(string $id): View
    {
        $guru = auth('guru')->user();
        $absensi = Absensi::with(['siswa', 'jadwal'])->findOrFail($id);
        $jadwalList = $guru->jadwal()->with('mataPelajaran')->get();

        return view('guru.kelola-absensi.edit', compact('absensi', 'jadwalList'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $absensi = Absensi::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'status_kehadiran' => 'required|in:hadir,izin,sakit,alpha',
        ], [
            'tanggal.required' => 'Tanggal wajib diisi',
            'id_jadwal.required' => 'Jadwal wajib dipilih',
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
