<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\JadwalHarian;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class JadwalHarianController extends Controller
{
    public function index(Request $request): View
    {
        $orangTua = auth('orangtua')->user();
        $siswa = $orangTua->siswa;

        if (! $siswa) {
            return view('orangtua.jadwal-harian.index', [
                'jadwal' => collect(),
                'tanggalList' => collect(),
                'tanggalDipilih' => null,
                'tema' => null,
                'catatan' => null,
            ]);
        }

        $tanggal = $request->input('tanggal', date('Y-m-d'));

        // Get jadwal for specific date and class
        $jadwal = JadwalHarian::whereDate('tanggal', $tanggal)
            ->where(function ($query) use ($siswa) {
                $query->where('kelas', $siswa->kelas)
                    ->orWhereNull('kelas');
            })
            ->orderBy('waktu_mulai', 'asc')
            ->get();

        // Get available dates
        $tanggalList = JadwalHarian::where(function ($query) use ($siswa) {
            $query->where('kelas', $siswa->kelas)
                ->orWhereNull('kelas');
        })
            ->distinct()
            ->orderBy('tanggal', 'desc')
            ->pluck('tanggal');

        // Get tema and catatan for the selected date
        $firstJadwal = $jadwal->first();
        $tema = $firstJadwal->tema ?? null;
        $catatan = $firstJadwal->catatan ?? null;

        return view('orangtua.jadwal-harian.index', compact('jadwal', 'tanggalList', 'tanggal', 'tema', 'catatan'));
    }
}
