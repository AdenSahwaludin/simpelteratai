<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Perilaku;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatatanPerilakuController extends Controller
{
    public function index(Request $request): View
    {
        $guru = auth('guru')->user();
        $search = $request->input('search');
        $kelas = $request->input('id_kelas');

        $perilaku = Perilaku::query()
            ->with(['siswa', 'guru'])
            ->where('id_guru', $guru->id_guru)
            ->when($search, function ($query, $search) {
                return $query->whereHas('siswa', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                })
                    ->orWhere('catatan_perilaku', 'like', "%{$search}%");
            })
            ->when($kelas, function ($query, $kelas) {
                return $query->whereHas('siswa', function ($q) use ($kelas) {
                    $q->where('id_kelas', $kelas);
                });
            })
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        $kelasList = Kelas::all();

        return view('guru.catatan-perilaku.index', compact('perilaku', 'kelasList', 'search', 'kelas'));
    }

    public function create(): View
    {
        $guru = auth('guru')->user();
        // Only show siswa from kelas where guru is wali
        $siswaList = Siswa::whereHas('kelas', function ($q) use ($guru) {
            $q->where('id_guru_wali', $guru->id_guru);
        })->orderBy('nama')->get();

        return view('guru.catatan-perilaku.create', compact('siswaList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $guru = auth('guru')->user();

        // Validate that siswa is in guru's wali kelas
        $siswaId = $request->input('id_siswa');
        $siswa = Siswa::findOrFail($siswaId);

        if (! $siswa->kelas || $siswa->kelas->id_guru_wali !== $guru->id_guru) {
            return redirect()->back()->withErrors('Anda hanya dapat menginput catatan perilaku untuk siswa yang menjadi tanggung jawab Anda sebagai wali kelas.');
        }

        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'tanggal' => 'required|date',
            'sosial' => 'required|in:Baik,Perlu dibina',
            'emosional' => 'required|in:Baik,Perlu dibina',
            'disiplin' => 'required|in:Baik,Perlu dibina',
            'catatan_perilaku' => 'required|string',
            'file_lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'id_siswa.required' => 'Siswa wajib dipilih',
            'tanggal.required' => 'Tanggal wajib diisi',
            'sosial.required' => 'Penilaian sosial wajib dipilih',
            'emosional.required' => 'Penilaian emosional wajib dipilih',
            'disiplin.required' => 'Penilaian disiplin wajib dipilih',
            'catatan_perilaku.required' => 'Catatan perilaku wajib diisi',
            'file_lampiran.mimes' => 'File harus berformat jpg, jpeg, png, atau pdf',
            'file_lampiran.max' => 'Ukuran file maksimal 2MB',
        ]);

        $perilaku = new Perilaku;
        $perilaku->id_perilaku = Perilaku::generateUniqueId();
        $perilaku->id_siswa = $validated['id_siswa'];
        $perilaku->id_guru = $guru->id_guru;
        $perilaku->tanggal = $validated['tanggal'];
        $perilaku->sosial = $validated['sosial'] === 'Baik' ? 5 : 3;
        $perilaku->emosional = $validated['emosional'] === 'Baik' ? 5 : 3;
        $perilaku->disiplin = $validated['disiplin'] === 'Baik' ? 5 : 3;
        $perilaku->catatan_perilaku = $validated['catatan_perilaku'];

        if ($request->hasFile('file_lampiran')) {
            $file = $request->file('file_lampiran');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('perilaku', $filename, 'public');
            $perilaku->file_lampiran = $filename;
        }

        $perilaku->save();

        return redirect()->route('guru.catatan-perilaku.index')->with('success', 'Catatan perilaku berhasil ditambahkan.');
    }

    public function edit(string $id): View
    {
        $guru = auth('guru')->user();
        $perilaku = Perilaku::with('siswa')->findOrFail($id);

        // Check authorization: hanya guru wali kelas dari siswa yang dapat edit
        if ($perilaku->siswa->kelas->id_guru_wali !== $guru->id_guru) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit catatan perilaku ini.');
        }

        // Only show siswa from kelas where guru is wali
        $siswaList = Siswa::whereHas('kelas', function ($q) use ($guru) {
            $q->where('id_guru_wali', $guru->id_guru);
        })->orderBy('nama')->get();

        return view('guru.catatan-perilaku.edit', compact('perilaku', 'siswaList'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $guru = auth('guru')->user();
        $perilaku = Perilaku::findOrFail($id);

        // Check authorization: hanya guru wali kelas dari siswa yang dapat update
        if ($perilaku->siswa->kelas->id_guru_wali !== $guru->id_guru) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah catatan perilaku ini.');
        }

        // Validate that siswa is in guru's wali kelas
        $siswaId = $request->input('id_siswa');
        $siswa = Siswa::findOrFail($siswaId);

        if (! $siswa->kelas || $siswa->kelas->id_guru_wali !== $guru->id_guru) {
            return redirect()->back()->withErrors('Anda hanya dapat mengubah catatan perilaku untuk siswa yang menjadi tanggung jawab Anda sebagai wali kelas.');
        }

        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'tanggal' => 'required|date',
            'sosial' => 'required|in:Baik,Perlu dibina',
            'emosional' => 'required|in:Baik,Perlu dibina',
            'disiplin' => 'required|in:Baik,Perlu dibina',
            'catatan_perilaku' => 'required|string',
            'file_lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'id_siswa.required' => 'Siswa wajib dipilih',
            'tanggal.required' => 'Tanggal wajib diisi',
            'sosial.required' => 'Penilaian sosial wajib dipilih',
            'emosional.required' => 'Penilaian emosional wajib dipilih',
            'disiplin.required' => 'Penilaian disiplin wajib dipilih',
            'catatan_perilaku.required' => 'Catatan perilaku wajib diisi',
            'file_lampiran.mimes' => 'File harus berformat jpg, jpeg, png, atau pdf',
            'file_lampiran.max' => 'Ukuran file maksimal 2MB',
        ]);

        $perilaku->id_siswa = $validated['id_siswa'];
        $perilaku->tanggal = $validated['tanggal'];
        $perilaku->sosial = $validated['sosial'] === 'Baik' ? 5 : 3;
        $perilaku->emosional = $validated['emosional'] === 'Baik' ? 5 : 3;
        $perilaku->disiplin = $validated['disiplin'] === 'Baik' ? 5 : 3;
        $perilaku->catatan_perilaku = $validated['catatan_perilaku'];

        if ($request->hasFile('file_lampiran')) {
            // Delete old file if exists
            if ($perilaku->file_lampiran && Storage::disk('public')->exists('perilaku/'.$perilaku->file_lampiran)) {
                Storage::disk('public')->delete('perilaku/'.$perilaku->file_lampiran);
            }

            $file = $request->file('file_lampiran');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('perilaku', $filename, 'public');
            $perilaku->file_lampiran = $filename;
        }

        $perilaku->save();

        return redirect()->route('guru.catatan-perilaku.index')->with('success', 'Catatan perilaku berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $perilaku = Perilaku::findOrFail($id);
        $perilaku->delete();

        return redirect()->route('guru.catatan-perilaku.index')->with('success', 'Catatan perilaku berhasil dihapus.');
    }
}
