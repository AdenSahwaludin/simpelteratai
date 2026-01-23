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

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_kelas' => ['required', 'string', 'max:50', 'unique:kelas,id_kelas'],
                'id_guru_wali' => ['required', 'string', 'exists:guru,id_guru'],
            ], [
                'id_kelas.unique' => 'ID Kelas sudah ada, gunakan ID yang berbeda.',
                'id_guru_wali.required' => 'Wali kelas harus dipilih.',
                'id_guru_wali.exists' => 'Guru yang dipilih tidak ditemukan.',
            ]);

            $kelas = Kelas::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil ditambahkan',
                'kelas' => $kelas,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()[array_key_first($e->errors())][0] ?? 'Validasi gagal',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
