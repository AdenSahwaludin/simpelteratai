<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $primaryKey = 'id_jadwal';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_jadwal',
        'id_guru',
        'id_mata_pelajaran',
        'ruang',
        'waktu_mulai',
        'waktu_selesai',
        'hari',
        'kelas',
        'tanggal_mulai',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
        'tanggal_mulai' => 'date',
    ];

    /**
     * Get the guru that owns the jadwal.
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    /**
     * Get the mata pelajaran that owns the jadwal.
     */
    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
    }

    /**
     * Generate unique ID with format J00001, J00002, etc.
     * Safe from duplicate even when data is deleted.
     * varchar(6): J + 5 digits = 6 characters
     */
    public static function generateUniqueId(): string
    {
        $lastId = static::orderByRaw('CAST(SUBSTRING(id_jadwal, 2) AS UNSIGNED) DESC')
            ->limit(1)
            ->pluck('id_jadwal')
            ->first();

        $nextNumber = $lastId
            ? (int) substr($lastId, 1) + 1
            : 1;

        return 'J'.str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get all pertemuan for this jadwal.
     */
    public function pertemuan(): HasMany
    {
        return $this->hasMany(Pertemuan::class, 'id_jadwal', 'id_jadwal');
    }

    /**
     * Get the siswa for the jadwal.
     */
    public function siswa(): BelongsToMany
    {
        return $this->belongsToMany(
            Siswa::class,
            'jadwal_siswa',
            'id_jadwal',
            'id_siswa'
        )->withTimestamps();
    }

    /**
     * Generate 14 pertemuan otomatis untuk jadwal ini
     */
    public function generatePertemuan(): void
    {
        if (! $this->tanggal_mulai) {
            throw new \Exception('Tanggal mulai semester belum diset');
        }

        $tanggalMulai = $this->tanggal_mulai;
        $hariMap = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
        ];

        $hariTarget = $hariMap[$this->hari];
        $tanggalPertemuan = $tanggalMulai->copy();

        // Cari hari pertama yang sesuai
        while ($tanggalPertemuan->dayOfWeek !== $hariTarget) {
            $tanggalPertemuan->addDay();
        }

        // Generate 14 pertemuan
        for ($i = 1; $i <= 14; $i++) {
            Pertemuan::create([
                'id_pertemuan' => $this->id_jadwal.'-P'.str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                'id_jadwal' => $this->id_jadwal,
                'pertemuan_ke' => $i,
                'tanggal' => $tanggalPertemuan->copy(),
                'status' => 'terjadwal',
            ]);

            $tanggalPertemuan->addWeek();
        }

        // Auto-assign semua siswa di kelas ke semua pertemuan
        $this->assignSiswaToPertemuan();
    }

    /**
     * Assign semua siswa di kelas ke semua pertemuan jadwal ini
     */
    public function assignSiswaToPertemuan(): void
    {
        $siswaList = Siswa::where('kelas', $this->kelas)->get();
        $pertemuanList = $this->pertemuan;

        foreach ($pertemuanList as $pertemuan) {
            foreach ($siswaList as $siswa) {
                Absensi::firstOrCreate([
                    'id_absensi' => Absensi::generateUniqueId(),
                    'id_siswa' => $siswa->id_siswa,
                    'id_pertemuan' => $pertemuan->id_pertemuan,
                ], [
                    'status_kehadiran' => 'belum_absen',
                ]);
            }
        }
    }
}
