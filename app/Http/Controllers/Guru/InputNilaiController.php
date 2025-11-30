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

        // Get mata pelajaran taught by this guru
        $mataPelajaranList = $guru->jadwal()->with('mataPelajaran')->get()->pluck('mataPelajaran')->unique('id_mata_pelajaran');

        $laporan = LaporanPerkembangan::query()
            ->with(['siswa', 'mataPelajaran'])
            ->whereHas('mataPelajaran.jadwal', function ($query) use ($guru) {
                $query->where('id_guru', $guru->id_guru);
            })
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
    {
        $guru = auth('guru')->user();
        $laporan = LaporanPerkembangan::with(['siswa', 'mataPelajaran'])->findOrFail($id);
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
        $laporan = LaporanPerkembangan::findOrFail($id);
        $laporan->delete();

        return redirect()->route('guru.input-nilai.index')->with('success', 'Nilai berhasil dihapus.');
    }

    /**
     * Show bulk entry form for nilai per jadwal/kelas
     */
    public function bulkIndex(Request $request): View
    {
        $guru = auth('guru')->user();

        // Get all jadwal for this guru with mata pelajaran
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

        // Verify jadwal belongs to this guru
        $jadwal = $guru->jadwal()
            ->where('id_jadwal', $id_jadwal)
            ->with('mataPelajaran', 'siswa')
            ->firstOrFail();

        // Get siswa registered for this jadwal
        $siswa = $jadwal->siswa()
            ->orderBy('siswa.nama')
            ->get();

        // Get existing nilai for these siswa in this mata pelajaran
        $existingNilai = LaporanPerkembangan::whereIn('id_siswa', $siswa->pluck('id_siswa'))
            ->where('id_mata_pelajaran', $jadwal->id_mata_pelajaran)
            ->get()
            ->keyBy('id_siswa');

        return response()->json([
            'siswa' => $siswa->map(fn ($s) => [
                'id_siswa' => $s->id_siswa,
                'nama' => $s->nama,
                'kelas' => $s->kelas,
            ]),
            'existingNilai' => $existingNilai->map(fn ($n) => [
                'nilai' => $n->nilai,
                'komentar' => $n->komentar,
                'id_laporan' => $n->id_laporan,
            ])->toArray(),
            'mata_pelajaran' => $jadwal->mataPelajaran->nama_mapel,
        ]);
    }

    /**
     * Store bulk nilai for multiple siswa in one jadwal
     */
    public function bulkStore(Request $request): RedirectResponse
    {
        $guru = auth('guru')->user();
        $id_jadwal = $request->input('id_jadwal');

        // Verify jadwal belongs to this guru
        $jadwal = $guru->jadwal()
            ->where('id_jadwal', $id_jadwal)
            ->firstOrFail();

        // Get nilai array from form
        $nilaiData = $request->input('nilai', []);
        $komentarData = $request->input('komentar', []);

        // Log for debugging
        Log::info('Bulk Store Request', [
            'jadwal' => $id_jadwal,
            'nilai_count' => count($nilaiData),
            'nilai_data' => $nilaiData,
            'komentar_data' => $komentarData,
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

            // Check if nilai already exists (update) or create new
            $existingNilai = LaporanPerkembangan::where('id_siswa', $id_siswa)
                ->where('id_mata_pelajaran', $jadwal->id_mata_pelajaran)
                ->first();

            if ($existingNilai) {
                // Update existing
                Log::info("Updating existing nilai for {$id_siswa}", [
                    'old_nilai' => $existingNilai->nilai,
                    'new_nilai' => $nilaiInt,
                    'id_laporan' => $existingNilai->id_laporan,
                ]);

                $existingNilai->update([
                    'nilai' => $nilaiInt,
                    'komentar' => $komentarData[$id_siswa] ?? null,
                ]);

                Log::info("Updated nilai for {$id_siswa}", [
                    'updated_nilai' => $existingNilai->fresh()->nilai,
                ]);
            } else {
                // Create new - generate unique ID
                $maxId = LaporanPerkembangan::orderByRaw('CAST(SUBSTRING(id_laporan, 3) AS UNSIGNED) DESC')
                    ->limit(1)
                    ->pluck('id_laporan')
                    ->first();

                $nextNumber = $maxId ? (int) substr($maxId, 2) + 1 : 1;
                $nextId = 'LP'.str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);

                Log::info("Creating new nilai for {$id_siswa}", [
                    'new_id' => $nextId,
                    'nilai' => $nilaiInt,
                ]);

                $created = LaporanPerkembangan::create([
                    'id_laporan' => $nextId,
                    'id_siswa' => $id_siswa,
                    'id_mata_pelajaran' => $jadwal->id_mata_pelajaran,
                    'nilai' => $nilaiInt,
                    'komentar' => $komentarData[$id_siswa] ?? null,
                ]);

                Log::info("Created nilai for {$id_siswa}", [
                    'created' => $created ? 'yes' : 'no',
                    'id_laporan' => $created->id_laporan ?? 'null',
                ]);
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
