<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanLengkap extends Model
{
    protected $table = 'laporan_lengkap';

    protected $primaryKey = 'id_laporan_lengkap';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_laporan_lengkap',
        'id_siswa',
        'id_guru',
        'periode_mulai',
        'periode_selesai',
        'catatan_guru',
        'target_pembelajaran',
        'pencapaian',
        'saran',
        'dikirim_ke_ortu',
        'tanggal_kirim',
    ];

    protected $casts = [
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
        'dikirim_ke_ortu' => 'boolean',
        'tanggal_kirim' => 'datetime',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    /**
     * Get aggregated attendance data for the period
     */
    public function getKehadiranData()
    {
        return Absensi::where('id_siswa', $this->id_siswa)
            ->whereHas('pertemuan', function ($query) {
                $query->whereBetween('tanggal', [$this->periode_mulai, $this->periode_selesai]);
            })
            ->selectRaw('status_kehadiran, COUNT(*) as total')
            ->groupBy('status_kehadiran')
            ->get();
    }

    /**
     * Get all comments for this laporan.
     */
    public function komentarList()
    {
        return $this->hasMany(Komentar::class, 'id_laporan_lengkap', 'id_laporan_lengkap')
            ->whereNull('parent_id')
            ->with(['orangTua', 'guru', 'replies'])
            ->latest();
    }

    /**
     * Get performance reports for the period
     */
    public function getNilaiData()
    {
        return LaporanPerkembangan::where('id_siswa', $this->id_siswa)
            ->whereBetween('created_at', [$this->periode_mulai, $this->periode_selesai])
            ->with('mataPelajaran')
            ->get();
    }

    /**
     * Get behavior notes for the period
     */
    public function getPerilakuData()
    {
        return Perilaku::where('id_siswa', $this->id_siswa)
            ->whereBetween('tanggal', [$this->periode_mulai, $this->periode_selesai])
            ->with('guru')
            ->latest('tanggal')
            ->get();
    }
}
