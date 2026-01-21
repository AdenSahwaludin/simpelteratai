<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelasList = Kelas::all();

        return view('admin.kelas.index', compact('kelasList'));
    }

    public function edit(Kelas $kelas)
    {
        $guru = Guru::all();

        return view('admin.kelas.edit', compact('kelas', 'guru'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'id_guru_wali' => ['nullable', 'string', 'exists:guru,id_guru'],
        ]);

        $kelas->update($validated);

        return redirect()->route('admin.kelas.index')->with('success', 'Wali kelas berhasil diperbarui.');
    }
}
