<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Komentar;
use App\Models\LaporanLengkap;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LaporanLengkapController extends Controller
{
    public function index(Request $request): View
    {
        $guru = Auth::guard('guru')->user();
        $search = $request->input('search');
        $kelas = $request->input('kelas');

        $laporan = LaporanLengkap::query()
            ->where('id_guru', $guru->id_guru)
            ->with(['siswa', 'guru'])
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
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->query());

        $kelasList = ['5A', '5B', '6A', '6B'];

        return view('guru.laporan-lengkap.index', compact('laporan', 'search', 'kelas', 'kelasList'));
    }

    public function create(): View
    {
        $guru = Auth::guard('guru')->user();

        $siswa = Siswa::whereHas('jadwal', function ($query) use ($guru) {
            $query->where('id_guru', $guru->id_guru);
        })->orderBy('nama')->get();

        return view('guru.laporan-lengkap.create', compact('siswa'));
    }

    public function store(Request $request): RedirectResponse
    {
        $guru = Auth::guard('guru')->user();

        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'catatan_guru' => 'required|string',
            'target_pembelajaran' => 'nullable|string',
            'pencapaian' => 'nullable|string',
            'saran' => 'nullable|string',
            'kirim_ke_ortu' => 'nullable|boolean',
        ], [
            'id_siswa.required' => 'Siswa wajib dipilih',
            'periode_mulai.required' => 'Periode mulai wajib diisi',
            'periode_selesai.required' => 'Periode selesai wajib diisi',
            'periode_selesai.after_or_equal' => 'Periode selesai harus setelah atau sama dengan periode mulai',
            'catatan_guru.required' => 'Catatan guru wajib diisi',
        ]);

        $laporan = new LaporanLengkap;
        $laporan->id_laporan_lengkap = 'LL'.str_pad((string) (LaporanLengkap::count() + 1), 3, '0', STR_PAD_LEFT);
        $laporan->id_siswa = $validated['id_siswa'];
        $laporan->id_guru = $guru->id_guru;
        $laporan->periode_mulai = $validated['periode_mulai'];
        $laporan->periode_selesai = $validated['periode_selesai'];
        $laporan->catatan_guru = $validated['catatan_guru'];
        $laporan->target_pembelajaran = $validated['target_pembelajaran'];
        $laporan->pencapaian = $validated['pencapaian'];
        $laporan->saran = $validated['saran'];
        $laporan->dikirim_ke_ortu = $request->has('kirim_ke_ortu');
        $laporan->tanggal_kirim = $request->has('kirim_ke_ortu') ? now() : null;
        $laporan->save();

        return redirect()->route('guru.laporan-lengkap.index')
            ->with('success', 'Laporan lengkap berhasil ditambahkan.');
    }

    public function show(string $id): View
    {
        $guru = Auth::guard('guru')->user();

        $laporan = LaporanLengkap::where('id_laporan_lengkap', $id)
            ->where('id_guru', $guru->id_guru)
            ->with(['siswa', 'guru', 'komentarList'])
            ->firstOrFail();

        $kehadiran = $laporan->getKehadiranData();
        $nilai = $laporan->getNilaiData();
        $perilaku = $laporan->getPerilakuData();

        return view('guru.laporan-lengkap.show', compact('laporan', 'kehadiran', 'nilai', 'perilaku'));
    }

    public function edit(string $id): View
    {
        $guru = Auth::guard('guru')->user();

        $laporan = LaporanLengkap::where('id_laporan_lengkap', $id)
            ->where('id_guru', $guru->id_guru)
            ->firstOrFail();

        $siswa = Siswa::whereHas('jadwal', function ($query) use ($guru) {
            $query->where('id_guru', $guru->id_guru);
        })->orderBy('nama')->get();

        return view('guru.laporan-lengkap.edit', compact('laporan', 'siswa'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $guru = Auth::guard('guru')->user();

        $laporan = LaporanLengkap::where('id_laporan_lengkap', $id)
            ->where('id_guru', $guru->id_guru)
            ->firstOrFail();

        $validated = $request->validate([
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'catatan_guru' => 'required|string',
            'target_pembelajaran' => 'nullable|string',
            'pencapaian' => 'nullable|string',
            'saran' => 'nullable|string',
            'kirim_ke_ortu' => 'nullable|boolean',
        ]);

        $laporan->periode_mulai = $validated['periode_mulai'];
        $laporan->periode_selesai = $validated['periode_selesai'];
        $laporan->catatan_guru = $validated['catatan_guru'];
        $laporan->target_pembelajaran = $validated['target_pembelajaran'];
        $laporan->pencapaian = $validated['pencapaian'];
        $laporan->saran = $validated['saran'];
        $laporan->dikirim_ke_ortu = $request->has('kirim_ke_ortu');
        $laporan->tanggal_kirim = $request->has('kirim_ke_ortu') ? now() : null;
        $laporan->save();

        return redirect()->route('guru.laporan-lengkap.index')
            ->with('success', 'Laporan lengkap berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $guru = Auth::guard('guru')->user();

        $laporan = LaporanLengkap::where('id_laporan_lengkap', $id)
            ->where('id_guru', $guru->id_guru)
            ->firstOrFail();

        $laporan->delete();

        return redirect()->route('guru.laporan-lengkap.index')
            ->with('success', 'Laporan lengkap berhasil dihapus.');
    }

    public function kirimKeOrangTua(string $id): RedirectResponse
    {
        $guru = Auth::guard('guru')->user();

        $laporan = LaporanLengkap::where('id_laporan_lengkap', $id)
            ->where('id_guru', $guru->id_guru)
            ->firstOrFail();

        $laporan->dikirim_ke_ortu = true;
        $laporan->tanggal_kirim = now();
        $laporan->save();

        return redirect()->route('guru.laporan-lengkap.show', $id)
            ->with('success', 'Laporan berhasil dikirim ke orang tua.');
    }

    public function previewData(Request $request)
    {
        $siswaId = $request->input('siswa_id');
        $periodeMulai = $request->input('periode_mulai');
        $periodeSelesai = $request->input('periode_selesai');

        if (! $siswaId || ! $periodeMulai || ! $periodeSelesai) {
            return response()->json(['error' => 'Data tidak lengkap'], 400);
        }

        $laporan = new LaporanLengkap;
        $laporan->id_siswa = $siswaId;
        $laporan->periode_mulai = $periodeMulai;
        $laporan->periode_selesai = $periodeSelesai;

        $kehadiran = $laporan->getKehadiranData();
        $nilai = $laporan->getNilaiData();
        $perilaku = $laporan->getPerilakuData();

        return response()->json([
            'kehadiran' => $kehadiran->map(function ($item) {
                return [
                    'status_kehadiran' => $item->status_kehadiran,
                    'total' => $item->total,
                ];
            }),
            'nilai' => $nilai->map(function ($item) {
                return [
                    'nilai' => $item->nilai,
                    'catatan' => $item->catatan,
                    'tanggal' => $item->tanggal,
                    'mata_pelajaran' => [
                        'nama_mapel' => $item->mataPelajaran->nama_mapel ?? '-',
                    ],
                ];
            }),
            'perilaku' => $perilaku->map(function ($item) {
                return [
                    'sosial' => $item->sosial,
                    'emosional' => $item->emosional,
                    'disiplin' => $item->disiplin,
                    'catatan' => $item->catatan_perilaku,
                    'tanggal' => $item->tanggal,
                    'guru' => [
                        'nama' => $item->guru->nama ?? '-',
                    ],
                ];
            }),
        ]);
    }

    public function storeKomentar(Request $request, string $id): RedirectResponse
    {
        $guru = Auth::guard('guru')->user();

        $laporan = LaporanLengkap::where('id_laporan_lengkap', $id)
            ->where('id_guru', $guru->id_guru)
            ->firstOrFail();

        $validated = $request->validate([
            'komentar' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:komentar,id_komentar',
        ], [
            'komentar.required' => 'Komentar wajib diisi',
            'komentar.max' => 'Komentar maksimal 1000 karakter',
        ]);

        // Generate ID
        $lastId = Komentar::orderByRaw('CAST(SUBSTRING(id_komentar, 2) AS UNSIGNED) DESC')
            ->limit(1)
            ->pluck('id_komentar')
            ->first();
        $nextNumber = $lastId ? (int) substr($lastId, 1) + 1 : 1;

        Komentar::create([
            'id_komentar' => 'K'.str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT),
            'id_guru' => $guru->id_guru,
            'id_laporan_lengkap' => $laporan->id_laporan_lengkap,
            'parent_id' => $validated['parent_id'] ?? null,
            'komentar' => $validated['komentar'],
        ]);

        return redirect()->route('guru.laporan-lengkap.show', $id)->with('success', 'Komentar berhasil ditambahkan.');
    }
}
