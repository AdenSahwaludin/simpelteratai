<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class JadwalSiswaController extends Controller
{
    /**
     * Display a listing of all schedules with their student assignments.
     */
    public function index(): View
    {
        $jadwals = Jadwal::with(['guru', 'mataPelajaran'])
            ->withCount('siswa')
            ->orderBy('ruang')
            ->orderBy('waktu_mulai')
            ->paginate(15);

        return view('admin.jadwal-siswa.index', compact('jadwals'));
    }

    /**
     * Display the form for managing students in a schedule.
     */
    public function edit(string $id): View
    {
        $jadwal = Jadwal::with(['guru', 'mataPelajaran', 'siswa'])->findOrFail($id);

        // Get all students grouped by class
        $siswaByClass = Siswa::orderBy('kelas')->orderBy('nama')->get()->groupBy('kelas');

        // Get IDs of already assigned students
        $assignedSiswaIds = $jadwal->siswa()->pluck('siswa.id_siswa')->toArray();

        return view('admin.jadwal-siswa.edit', compact('jadwal', 'siswaByClass', 'assignedSiswaIds'));
    }

    /**
     * Update the students for a schedule.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $jadwal = Jadwal::findOrFail($id);

        $validated = $request->validate([
            'siswa' => 'array',
            'siswa.*' => 'exists:siswa,id_siswa',
        ], [
            'siswa.*.exists' => 'Data siswa tidak valid',
        ]);

        $siswaIds = $validated['siswa'] ?? [];

        // Sync the students with the schedule
        $jadwal->siswa()->sync($siswaIds);

        return redirect()->route('admin.jadwal.show', $jadwal->id_jadwal)
            ->with('success', 'Daftar siswa untuk jadwal berhasil diperbarui.');
    }
}
