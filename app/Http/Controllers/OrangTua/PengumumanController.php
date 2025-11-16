<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the announcements.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $pengumuman = Pengumuman::query()
            ->when($search, function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('isi', 'like', "%{$search}%");
            })
            ->latest('tanggal')
            ->paginate(15)
            ->appends($request->query());

        return view('orangtua.pengumuman.index', compact('pengumuman', 'search'));
    }

    /**
     * Display the specified announcement.
     */
    public function show(string $id): View
    {
        $pengumuman = Pengumuman::findOrFail($id);

        return view('orangtua.pengumuman.show', compact('pengumuman'));
    }
}
