<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $pengumuman = Pengumuman::query()
            ->when($search, function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('isi', 'like', "%{$search}%");
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(15)
            ->appends($request->query());

        return view('guru.pengumuman.index', compact('pengumuman', 'search'));
    }

    public function show(string $id): View
    {
        $pengumuman = Pengumuman::findOrFail($id);

        return view('guru.pengumuman.show', compact('pengumuman'));
    }
}
