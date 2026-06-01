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
        $guru = Guru::all();

        return view('admin.kelas.index', compact('kelasList', 'guru'));
    }

    public function edit(Kelas $kelas)
    {
        $guru = Guru::all();

        return view('admin.kelas.edit', compact('kelas', 'guru'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'id_guru_wali' => ['nullable', 'string', 'exists:guru,id_guru', 'unique:kelas,id_guru_wali,' . $kelas->id_kelas . ',id_kelas'],
        ], [
            'id_guru_wali.unique' => 'Guru ini sudah menjadi wali kelas di kelas lain.',
            'id_guru_wali.exists' => 'Guru yang dipilih tidak ditemukan.',
        ]);

        $kelas->update($validated);

        return redirect()->route('admin.kelas.index')->with('success', 'Wali kelas berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_kelas' => ['required', 'string', 'max:50', 'unique:kelas,id_kelas'],
                'id_guru_wali' => ['nullable', 'string', 'exists:guru,id_guru', 'unique:kelas,id_guru_wali'],
            ], [
                'id_kelas.unique' => 'ID Kelas sudah ada, gunakan ID yang berbeda.',
                'id_kelas.required' => 'ID Kelas (Nama Kelas) harus diisi.',
                'id_guru_wali.exists' => 'Guru yang dipilih tidak ditemukan.',
                'id_guru_wali.unique' => 'Guru ini sudah menjadi wali kelas di kelas lain.',
            ]);

            $kelas = Kelas::create($validated);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kelas berhasil ditambahkan',
                    'kelas' => $kelas,
                ]);
            }

            return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->errors()[array_key_first($e->errors())][0] ?? 'Validasi gagal',
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function updateNama(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'id_kelas_baru' => ['required', 'string', 'max:50', 'unique:kelas,id_kelas,' . $kelas->id_kelas . ',id_kelas'],
        ], [
            'id_kelas_baru.required' => 'ID Kelas (Nama Kelas) harus diisi.',
            'id_kelas_baru.unique' => 'ID Kelas sudah digunakan oleh kelas lain.',
        ]);

        $kelas->id_kelas = $validated['id_kelas_baru'];
        $kelas->save();

        return redirect()->route('admin.kelas.index')->with('success', 'Nama Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        try {
            $kelas->delete();
            return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.kelas.index')->with('error', 'Gagal menghapus kelas karena masih ada data yang terkait.');
        }
    }
}
