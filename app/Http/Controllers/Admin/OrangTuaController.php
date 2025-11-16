<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrangTua;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrangTuaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $orangTua = OrangTua::query()
            ->withCount('siswa')
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('id_orang_tua', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->paginate(20);

        return view('admin.orangtua.index', compact('orangTua', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.orangtua.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:orang_tua,email',
            'password' => 'required|string|min:6|confirmed',
            'no_telpon' => 'required|string|max:20',
        ], [
            'nama.required' => 'Nama orang tua wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'no_telpon.required' => 'No. telepon wajib diisi',
        ]);

        $orangTua = new OrangTua;
        $orangTua->id_orang_tua = 'O' . str_pad((string)(OrangTua::count() + 1), 3, '0', STR_PAD_LEFT);
        $orangTua->nama = $validated['nama'];
        $orangTua->email = $validated['email'];
        $orangTua->password = $validated['password'];
        $orangTua->no_telpon = $validated['no_telpon'];
        $orangTua->save();

        return redirect()->route('admin.orangtua.index')->with('success', 'Data orang tua berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $orangTua = OrangTua::query()
            ->where('id_orang_tua', $id)
            ->with('siswa')
            ->firstOrFail();

        return view('admin.orangtua.show', compact('orangTua'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $orangTua = OrangTua::findOrFail($id);

        return view('admin.orangtua.edit', compact('orangTua'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $orangTua = OrangTua::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:orang_tua,email,' . $id . ',id_orang_tua',
            'password' => 'nullable|string|min:6|confirmed',
            'no_telpon' => 'required|string|max:20',
        ], [
            'nama.required' => 'Nama orang tua wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'no_telpon.required' => 'No. telepon wajib diisi',
        ]);

        $orangTua->nama = $validated['nama'];
        $orangTua->email = $validated['email'];
        if (!empty($validated['password'])) {
            $orangTua->password = $validated['password'];
        }
        $orangTua->no_telpon = $validated['no_telpon'];
        $orangTua->save();

        return redirect()->route('admin.orangtua.index')->with('success', 'Data orang tua berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $orangTua = OrangTua::findOrFail($id);

        if ($orangTua->siswa()->count() > 0) {
            return redirect()->route('admin.orangtua.index')
                ->with('error', 'Tidak dapat menghapus orang tua yang masih memiliki siswa.');
        }

        $orangTua->delete();

        return redirect()->route('admin.orangtua.index')->with('success', 'Data orang tua berhasil dihapus.');
    }
}
