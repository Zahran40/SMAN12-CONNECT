<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailAbsensi extends Model
{
    protected $table = 'detail_absensi';
    protected $primaryKey = 'id_detail_absensi';
    public $timestamps = false;

    protected $fillable = [
        'pertemuan_id',
        'siswa_id',
        'status_kehadiran',
        'keterangan',
        'dicatat_pada',
        'latitude',
        'longitude',
        'alamat_lengkap',
    ];

    protected $casts = [
        'dicatat_pada' => 'datetime',
    ];

    /**
     * Relasi ke Pertemuan
     */
    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class, 'pertemuan_id', 'id_pertemuan');
    }

    /**
     * Relasi ke Siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id_siswa');
    }

    /**
     * Scope untuk filter absensi hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereHas('pertemuan', function($q) {
            $q->whereDate('tanggal_pertemuan', now()->toDateString());
        });
    }

    /**
     * Scope untuk filter by siswa
     */
    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    /**
     * Check if this absensi record can be edited
     */
    public function canEdit($user = null)
    {
        // Delegate to pertemuan's canEditAbsensi method
        return $this->pertemuan ? $this->pertemuan->canEditAbsensi($user) : false;
    }
}
