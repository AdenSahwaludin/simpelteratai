<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'tanggal');
        $direction = $request->input('direction', 'desc');

        // Validasi sort column
        $allowedSort = ['id_pengumuman', 'judul', 'tanggal'];
        if (! in_array($sort, $allowedSort)) {
            $sort = 'tanggal';
        }

        $pengumuman = Pengumuman::query()
            ->with('admin')
            ->when($search, function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('isi', 'like', "%{$search}%");
            })
            ->orderBy($sort, $direction)
            ->paginate(15)
            ->appends($request->query());

        return view('admin.pengumuman.index', compact('pengumuman', 'search', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.pengumuman.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
            'publikasi' => 'nullable|boolean',
        ], [
            'judul.required' => 'Judul pengumuman wajib diisi',
            'isi.required' => 'Isi pengumuman wajib diisi',
            'tanggal.required' => 'Tanggal wajib diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
        ]);

        $admin = Auth::guard('admin')->user();

        $pengumuman = new Pengumuman;
        $pengumuman->id_pengumuman = Pengumuman::generateUniqueId();
        $pengumuman->judul = $validated['judul'];
        $pengumuman->isi = $validated['isi'];
        $pengumuman->tanggal = $validated['tanggal'];
        $pengumuman->publikasi = $request->has('publikasi');
        $pengumuman->id_admin = $admin->id_admin;
        $pengumuman->save();

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $pengumuman = Pengumuman::query()
            ->where('id_pengumuman', $id)
            ->with('admin')
            ->firstOrFail();

        return view('admin.pengumuman.show', compact('pengumuman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $pengumuman = Pengumuman::findOrFail($id);

        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
            'publikasi' => 'nullable|boolean',
        ], [
            'judul.required' => 'Judul pengumuman wajib diisi',
            'isi.required' => 'Isi pengumuman wajib diisi',
            'tanggal.required' => 'Tanggal wajib diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
        ]);

        $pengumuman->judul = $validated['judul'];
        $pengumuman->isi = $validated['isi'];
        $pengumuman->tanggal = $validated['tanggal'];
        $pengumuman->publikasi = $request->has('publikasi');
        $pengumuman->save();

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
