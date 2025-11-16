<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'nama');
        $direction = $request->input('direction', 'asc');

        // Validasi sort column
        $allowedSort = ['id_guru', 'nama', 'email', 'no_telpon'];
        if (! in_array($sort, $allowedSort)) {
            $sort = 'nama';
        }

        $guru = Guru::query()
            ->withCount('jadwal')
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('id_guru', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy($sort, $direction)
            ->paginate(20)
            ->appends($request->query());

        return view('admin.guru.index', compact('guru', 'search', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.guru.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:guru,email',
            'password' => 'required|string|min:6|confirmed',
            'no_telpon' => 'required|string|max:20',
        ], [
            'nama.required' => 'Nama guru wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'no_telpon.required' => 'No. telepon wajib diisi',
        ]);

        $guru = new Guru;
        $guru->id_guru = 'G'.str_pad((string) (Guru::count() + 1), 2, '0', STR_PAD_LEFT);
        $guru->nama = $validated['nama'];
        $guru->email = $validated['email'];
        $guru->password = $validated['password'];
        $guru->no_telpon = $validated['no_telpon'];
        $guru->save();

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $guru = Guru::query()
            ->where('id_guru', $id)
            ->with('jadwal.mataPelajaran')
            ->firstOrFail();

        return view('admin.guru.show', compact('guru'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $guru = Guru::findOrFail($id);

        return view('admin.guru.edit', compact('guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $guru = Guru::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:guru,email,'.$id.',id_guru',
            'password' => 'nullable|string|min:6|confirmed',
            'no_telpon' => 'required|string|max:20',
        ], [
            'nama.required' => 'Nama guru wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'no_telpon.required' => 'No. telepon wajib diisi',
        ]);

        $guru->nama = $validated['nama'];
        $guru->email = $validated['email'];
        if (! empty($validated['password'])) {
            $guru->password = $validated['password'];
        }
        $guru->no_telpon = $validated['no_telpon'];
        $guru->save();

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $guru = Guru::findOrFail($id);

        if ($guru->jadwal()->count() > 0) {
            return redirect()->route('admin.guru.index')
                ->with('error', 'Tidak dapat menghapus guru yang masih memiliki jadwal mengajar.');
        }

        $guru->delete();

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil dihapus.');
    }
}
