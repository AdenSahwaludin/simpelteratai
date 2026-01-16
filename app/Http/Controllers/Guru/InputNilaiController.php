<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\LaporanPerkembangan;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InputNilaiController extends Controller
{
    public function index(Request $request): View
    {
        $guru = auth('guru')->user();
        $search = $request->input('search');
        $kelas = $request->input('kelas');
        $mataPelajaran = $request->input('mata_pelajaran');

        /** @var \App\Models\Guru $guru */
        $mataPelajaranList = $guru->jadwal()->with('mataPelajaran')->get()->pluck('mataPelajaran')->unique('id_mata_pelajaran');

        $laporan = LaporanPerkembangan::query()
            ->with(['siswa', 'mataPelajaran', 'absensi.pertemuan'])
            ->where('id_guru', $guru->id_guru)
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

    public function edit(string $id): View
    {/** @var \App\Models\Guru $guru */
        $guru = auth('guru')->user();
        $laporan = LaporanPerkembangan::with(['siswa', 'mataPelajaran', 'absensi'])
            ->where('id_guru', $guru->id_guru)
            ->findOrFail($id);
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
        $guru = auth('guru')->user();
        /** @var \App\Models\Guru $guru */
        $laporan = LaporanPerkembangan::where('id_guru', $guru->id_guru)->findOrFail($id);
        $laporan->delete();

        return redirect()->route('guru.input-nilai.index')->with('success', 'Nilai berhasil dihapus.');
    }

    /**
     * Show bulk entry form for nilai per jadwal/kelas
     */
    public function bulkIndex(Request $request): View
    {
        $guru = auth('guru')->user();
        /** @var \App\Models\Guru $guru */
        $jadwalList = $guru->jadwal()->with('mataPelajaran')->get();

        // Get unique class list
        $kelasList = Siswa::distinct()->pluck('kelas')->sort()->values();

        return view('guru.input-nilai.bulk-create', compact('jadwalList', 'kelasList'));
    }

    /**
     * Load siswa by jadwal for AJAX (returns with existing nilai if available)
     */
    public function loadSiswaByJadwal(Request $request)
    {
        $id_jadwal = $request->input('id_jadwal');
        $guru = auth('guru')->user();

        /** @var \App\Models\Guru $guru */
        $jadwal = $guru->jadwal()
            ->where('id_jadwal', $id_jadwal)
            ->with('mataPelajaran', 'siswa', 'pertemuan')
            ->firstOrFail();

        // Get siswa registered for this jadwal
        $siswa = $jadwal->siswa()
            ->orderBy('siswa.nama')
            ->get();

        // Get all pertemuan for this jadwal with past dates only
        $pertemuanList = $jadwal->pertemuan()
            ->whereDate('tanggal', '<=', today())
            ->orderBy('tanggal')
            ->get()
            ->map(fn ($p) => [
                'id_pertemuan' => $p->id_pertemuan,
                'pertemuan_ke' => $p->pertemuan_ke,
                'tanggal' => $p->tanggal,
                'tanggal_formatted' => \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y'),
            ]);

        return response()->json([
            'siswa' => $siswa->map(fn ($s) => [
                'id_siswa' => $s->id_siswa,
                'nama' => $s->nama,
                'kelas' => $s->kelas,
            ]),
            'pertemuan' => $pertemuanList,
            'mata_pelajaran' => $jadwal->mataPelajaran->nama_mapel,
        ]);
    }

    /**
     * Load absensi by pertemuan for AJAX (returns absensi list with existing nilai)
     */
    public function loadAbsensiByPertemuan(Request $request)
    {
        $id_pertemuan = $request->input('id_pertemuan');
        $id_jadwal = $request->input('id_jadwal');
        $guru = auth('guru')->user();

        /** @var \App\Models\Guru $guru */
        // Verify jadwal belongs to guru
        $jadwal = $guru->jadwal()->where('id_jadwal', $id_jadwal)->firstOrFail();

        // Get all absensi for this pertemuan
        $absensiList = \App\Models\Absensi::where('id_pertemuan', $id_pertemuan)
            ->with(['siswa', 'laporanPerkembangan' => function ($q) use ($guru) {
                $q->where('id_guru', $guru->id_guru);
            }])
            ->get();

        return response()->json([
            'absensiList' => $absensiList->map(function ($a) {
                // Get first laporan (should only be one per guru per absensi)
                $laporan = $a->laporanPerkembangan->first();

                return [
                    'id_absensi' => $a->id_absensi,
                    'id_siswa' => $a->id_siswa,
                    'nilai' => $laporan ? [
                        'nilai' => $laporan->nilai,
                        'komentar' => $laporan->komentar,
                        'id_laporan' => $laporan->id_laporan,
                    ] : null,
                ];
            }),
        ]);
    }

    /**
     * Store bulk nilai for multiple siswa in one jadwal
     */
    public function bulkStore(Request $request): RedirectResponse
    {
        $guru = auth('guru')->user();
        $id_jadwal = $request->input('id_jadwal');

        // Get nilai array from form
        $nilaiData = $request->input('nilai', []);
        $komentarData = $request->input('komentar', []);
        $absensiData = $request->input('id_absensi', []);  // Array id_absensi per siswa

        // Validate has absensi data
        if (empty($absensiData)) {
            return back()->with('error', 'Pilih pertemuan/absensi terlebih dahulu.');
        }

        /** @var \App\Models\Guru $guru */
        $jadwal = $guru->jadwal()
            ->where('id_jadwal', $id_jadwal)
            ->firstOrFail();

        // Log for debugging
        Log::info('Bulk Store Request', [
            'jadwal' => $id_jadwal,
            'guru' => $guru->id_guru,
            'nilai_count' => count($nilaiData),
            'absensi_count' => count($absensiData),
        ]);

        if (empty($nilaiData)) {
            return back()->with('error', 'Tidak ada nilai yang dimasukkan.');
        }

        // Get siswa yang terdaftar untuk jadwal ini
        $registeredSiswa = $jadwal->siswa()->pluck('siswa.id_siswa')->toArray();

        $savedCount = 0;
        $errors = [];

        foreach ($nilaiData as $id_siswa => $nilai) {
            // Skip if nilai is empty
            if ($nilai === '' || $nilai === null) {
                continue;
            }

            // Get absensi for this siswa
            $id_absensi = $absensiData[$id_siswa] ?? null;
            if (! $id_absensi) {
                $errors[] = "Absensi tidak ditemukan untuk siswa (ID: $id_siswa).";

                continue;
            }

            // Convert to integer and validate nilai
            $nilaiInt = (int) $nilai;
            if (! is_numeric($nilai) || $nilaiInt < 0 || $nilaiInt > 100) {
                $errors[] = "Nilai siswa (ID: $id_siswa) tidak valid (harus 0-100).";

                continue;
            }

            // Check if siswa is registered for this jadwal
            if (! in_array($id_siswa, $registeredSiswa)) {
                $errors[] = "Siswa (ID: $id_siswa) tidak terdaftar untuk jadwal ini.";

                continue;
            }

            // Verify absensi belongs to this siswa and jadwal
            $absensiCheck = \App\Models\Absensi::where('id_absensi', $id_absensi)
                ->where('id_siswa', $id_siswa)
                ->whereHas('pertemuan', function ($q) use ($id_jadwal) {
                    $q->where('id_jadwal', $id_jadwal);
                })
                ->exists();

            if (! $absensiCheck) {
                $errors[] = "Absensi tidak valid untuk siswa (ID: $id_siswa).";

                continue;
            }

            // Check if nilai already exists for this siswa + absensi (update) or create new
            $existingNilai = LaporanPerkembangan::where('id_siswa', $id_siswa)
                ->where('id_absensi', $id_absensi)
                ->where('id_guru', $guru->id_guru)
                ->first();

            if ($existingNilai) {
                // Update existing
                $existingNilai->update([
                    'nilai' => $nilaiInt,
                    'komentar' => $komentarData[$id_siswa] ?? null,
                ]);

                Log::info("Updated nilai for {$id_siswa}");
            } else {
                // Create new - generate unique ID
                $maxId = LaporanPerkembangan::orderByRaw('CAST(SUBSTRING(id_laporan, 3) AS UNSIGNED) DESC')
                    ->limit(1)
                    ->pluck('id_laporan')
                    ->first();

                $nextNumber = $maxId ? (int) substr($maxId, 2) + 1 : 1;
                $nextId = 'LP'.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);

                LaporanPerkembangan::create([
                    'id_laporan' => $nextId,
                    'id_siswa' => $id_siswa,
                    'id_guru' => $guru->id_guru,
                    'id_mata_pelajaran' => $jadwal->id_mata_pelajaran,
                    'nilai' => $nilaiInt,
                    'id_absensi' => $id_absensi,
                    'komentar' => $komentarData[$id_siswa] ?? null,
                ]);

                Log::info("Created nilai for {$id_siswa} with id {$nextId}");
            }

            $savedCount++;
        }

        $message = "Berhasil menyimpan nilai untuk {$savedCount} siswa.";
        if (! empty($errors)) {
            $message .= ' '.implode(' ', $errors);
        }

        return redirect()->route('guru.input-nilai.index')->with('success', $message);
    }
}
