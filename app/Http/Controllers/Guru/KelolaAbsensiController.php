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
        $id_jadwal = $request->input('id_jadwal');

        // Only query if at least one filter is provided
        $hasFilter = $search || $tanggal || $kelas || $id_jadwal;
        $absensi = null;

        if ($hasFilter) {
            $absensi = Absensi::query()
                ->with(['siswa', 'pertemuan.jadwal.mataPelajaran'])
                ->whereHas('pertemuan.jadwal', function ($query) use ($guru) {
                    $query->where('id_guru', $guru->id_guru);
                })
                ->when($search, function ($query, $search) {
                    return $query->whereHas('siswa', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
                })
                ->when($tanggal, function ($query, $tanggal) {
                    return $query->whereHas('pertemuan', function ($q) use ($tanggal) {
                        $q->whereDate('tanggal', $tanggal);
                    });
                })
                ->when($kelas, function ($query, $kelas) {
                    return $query->whereHas('siswa', function ($q) use ($kelas) {
                        $q->where('kelas', $kelas);
                    });
                })
                ->when($id_jadwal, function ($query, $id_jadwal) {
                    return $query->whereHas('pertemuan', function ($q) use ($id_jadwal) {
                        $q->where('id_jadwal', $id_jadwal);
                    });
                })
                ->paginate(20)
                ->appends($request->query());
        }

        // Get unique classes from guru's students
        $kelasList = Siswa::distinct('kelas')->pluck('kelas');

        /** @var \App\Models\Guru $guru */
        $jadwalList = $guru->jadwal()->with('mataPelajaran')->get();

        return view('guru.kelola-absensi.index', compact('absensi', 'search', 'tanggal', 'kelas', 'id_jadwal', 'kelasList', 'jadwalList', 'hasFilter'));
    }

    public function create(): View
    {
        $guru = auth('guru')->user();
        /** @var \App\Models\Guru $guru */
        $jadwalList = $guru->jadwal()->with('mataPelajaran')->get();

        return view('guru.kelola-absensi.create', compact('jadwalList'));
    }

    /**
     * Load students for a specific schedule with existing attendance (AJAX endpoint)
     */
    public function loadSiswaByKelas(Request $request): \Illuminate\Http\JsonResponse
    {
        $id_jadwal = $request->input('id_jadwal');
        $tanggal = $request->input('tanggal');

        // Get students registered for this schedule from jadwal_siswa pivot table
        $siswa = Siswa::query()
            ->whereHas('jadwal', function ($query) use ($id_jadwal) {
                $query->where('jadwal.id_jadwal', $id_jadwal);
            })
            ->orderBy('nama')
            ->get(['id_siswa', 'nama', 'kelas']);

        // Get existing attendance records through pertemuan
        $existingAbsensi = [];
        if ($tanggal) {
            // Find pertemuan for this jadwal and tanggal
            $pertemuan = \App\Models\Pertemuan::where('id_jadwal', $id_jadwal)
                ->whereDate('tanggal', $tanggal)
                ->first();

            if ($pertemuan) {
                $existingAbsensi = Absensi::where('id_pertemuan', $pertemuan->id_pertemuan)
                    ->pluck('status_kehadiran', 'id_siswa')
                    ->toArray();
            }
        }

        return response()->json([
            'siswa' => $siswa,
            'existingAbsensi' => $existingAbsensi,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id_jadwal',
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*' => 'required|in:hadir,izin,sakit,alpha',
        ], [
            'id_jadwal.required' => 'Jadwal wajib dipilih',
            'tanggal.required' => 'Tanggal wajib diisi',
            'absensi.required' => 'Data absensi wajib diisi',
        ]);

        $guru = auth('guru')->user();
        /** @var \App\Models\Guru $guru */
        $jadwal = $guru->jadwal()->find($validated['id_jadwal']);
        if (! $jadwal) {
            return back()->with('error', 'Jadwal tidak ditemukan atau tidak milik Anda.');
        }

        // Find or create pertemuan for this jadwal and tanggal
        $pertemuan = \App\Models\Pertemuan::where('id_jadwal', $validated['id_jadwal'])
            ->whereDate('tanggal', $validated['tanggal'])
            ->first();

        if (! $pertemuan) {
            // Create new pertemuan automatically
            $lastPertemuan = \App\Models\Pertemuan::orderByRaw('CAST(SUBSTRING(id_pertemuan, 2) AS UNSIGNED) DESC')
                ->limit(1)
                ->pluck('id_pertemuan')
                ->first();
            $nextNumber = $lastPertemuan ? (int) substr($lastPertemuan, 1) + 1 : 1;

            // Get pertemuan_ke for this jadwal
            $pertemuanKe = \App\Models\Pertemuan::where('id_jadwal', $validated['id_jadwal'])
                ->max('pertemuan_ke');
            $pertemuanKe = $pertemuanKe ? $pertemuanKe + 1 : 1;

            $pertemuan = \App\Models\Pertemuan::create([
                'id_pertemuan' => 'P'.str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT),
                'id_jadwal' => $validated['id_jadwal'],
                'pertemuan_ke' => $pertemuanKe,
                'tanggal' => $validated['tanggal'],
                'materi' => 'Pertemuan otomatis dari absensi',
                'status' => 'selesai',
            ]);
        }

        // Get students registered for this schedule from pivot table
        $siswaList = $jadwal->siswa()->get();

        if ($siswaList->isEmpty()) {
            return back()->with('error', 'Tidak ada siswa yang terdaftar untuk jadwal ini.');
        }

        $savedCount = 0;

        // Create/update absensi records for registered students only
        foreach ($siswaList as $siswa) {
            $status = $validated['absensi'][$siswa->id_siswa] ?? null;

            if ($status) {
                // Check if absensi already exists for this student and pertemuan
                $absensi = Absensi::where('id_siswa', $siswa->id_siswa)
                    ->where('id_pertemuan', $pertemuan->id_pertemuan)
                    ->first();

                if (! $absensi) {
                    // Generate new ID
                    $lastId = Absensi::orderByRaw('CAST(SUBSTRING(id_absensi, 2) AS UNSIGNED) DESC')->limit(1)->pluck('id_absensi')->first();
                    $nextNumber = $lastId ? (int) substr($lastId, 1) + 1 : 1;
                    $absensi = new Absensi([
                        'id_absensi' => 'A'.str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT),
                        'id_siswa' => $siswa->id_siswa,
                        'id_pertemuan' => $pertemuan->id_pertemuan,
                    ]);
                }

                $absensi->status_kehadiran = $status;
                $absensi->save();
                $savedCount++;
            }
        }

        if ($savedCount === 0) {
            return back()->with('error', 'Tidak ada status kehadiran yang diisi.');
        }

        return redirect()->route('guru.kelola-absensi.index')->with('success', "Absensi berhasil disimpan untuk {$savedCount} siswa.");
    }

    public function edit(string $id): View
    {
        $guru = auth('guru')->user();
        $absensi = Absensi::with(['siswa', 'pertemuan.jadwal'])->findOrFail($id);
        /** @var \App\Models\Guru $guru */
        $jadwalList = $guru->jadwal()->with('mataPelajaran')->get();

        return view('guru.kelola-absensi.edit', compact('absensi', 'jadwalList'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $absensi = Absensi::findOrFail($id);

        $validated = $request->validate([
            'status_kehadiran' => 'required|in:hadir,izin,sakit,alpha,belum_absen',
        ], [
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

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->route('guru.kelola-absensi.index')->with('error', 'Tidak ada absensi yang dipilih.');
        }

        $deletedCount = Absensi::whereIn('id_absensi', $ids)->delete();

        return redirect()->route('guru.kelola-absensi.index')->with('success', "{$deletedCount} absensi berhasil dihapus.");
    }
}
