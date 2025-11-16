<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Komentar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KomentarController extends Controller
{
    /**
     * Display a listing of the comments.
     */
    public function index(Request $request): View
    {
        $orangTua = auth('orangtua')->user();
        $search = $request->input('search');

        $komentar = Komentar::query()
            ->where('id_orang_tua', $orangTua->id_orang_tua)
            ->with('orangTua')
            ->when($search, function ($query, $search) {
                return $query->where('komentar', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        return view('orangtua.komentar.index', compact('komentar', 'search'));
    }

    /**
     * Show the form for creating a new comment.
     */
    public function create(): View
    {
        return view('orangtua.komentar.create');
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $orangTua = auth('orangtua')->user();

        $validated = $request->validate([
            'komentar' => 'required|string',
        ], [
            'komentar.required' => 'Komentar wajib diisi',
        ]);

        $komentar = new Komentar;
        $komentar->id_komentar = 'K'.str_pad((string) (Komentar::count() + 1), 3, '0', STR_PAD_LEFT);
        $komentar->id_orang_tua = $orangTua->id_orang_tua;
        $komentar->komentar = $validated['komentar'];
        $komentar->save();

        return redirect()->route('orangtua.komentar.index')->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Display the specified comment.
     */
    public function show(string $id): View
    {
        $orangTua = auth('orangtua')->user();

        $komentar = Komentar::query()
            ->where('id_komentar', $id)
            ->where('id_orang_tua', $orangTua->id_orang_tua)
            ->with('orangTua')
            ->firstOrFail();

        return view('orangtua.komentar.show', compact('komentar'));
    }

    /**
     * Show the form for editing the specified comment.
     */
    public function edit(string $id): View
    {
        $orangTua = auth('orangtua')->user();

        $komentar = Komentar::query()
            ->where('id_komentar', $id)
            ->where('id_orang_tua', $orangTua->id_orang_tua)
            ->firstOrFail();

        return view('orangtua.komentar.edit', compact('komentar'));
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $orangTua = auth('orangtua')->user();

        $komentar = Komentar::query()
            ->where('id_komentar', $id)
            ->where('id_orang_tua', $orangTua->id_orang_tua)
            ->firstOrFail();

        $validated = $request->validate([
            'komentar' => 'required|string',
        ], [
            'komentar.required' => 'Komentar wajib diisi',
        ]);

        $komentar->komentar = $validated['komentar'];
        $komentar->save();

        return redirect()->route('orangtua.komentar.index')->with('success', 'Komentar berhasil diperbarui.');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $orangTua = auth('orangtua')->user();

        $komentar = Komentar::query()
            ->where('id_komentar', $id)
            ->where('id_orang_tua', $orangTua->id_orang_tua)
            ->firstOrFail();

        $komentar->delete();

        return redirect()->route('orangtua.komentar.index')->with('success', 'Komentar berhasil dihapus.');
    }
}
