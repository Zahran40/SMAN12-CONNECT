<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $table = 'materi';
    protected $primaryKey = 'id_materi';
    public $timestamps = false;

    protected $fillable = [
        'jadwal_id',
        'pertemuan_id',
        'judul_materi',
        'deskripsi',
        'file_path',
        'tgl_upload',
    ];

    protected $casts = [
        'tgl_upload' => 'datetime',
    ];

    /**
     * Accessor for deskripsi_materi (alias for deskripsi)
     */
    public function getDeskripsiMateriAttribute()
    {
        return $this->attributes['deskripsi'];
    }

    /**
     * Relasi ke Jadwal Pelajaran
     */
    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id', 'id_jadwal');
    }

    /**
     * Relasi ke Pertemuan
     */
    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class, 'pertemuan_id', 'id_pertemuan');
    }

    /**
     * Relasi langsung ke Mata Pelajaran melalui Jadwal
     */
    public function mataPelajaran()
    {
        return $this->hasOneThrough(
            MataPelajaran::class,
            JadwalPelajaran::class,
            'id_jadwal',      // Foreign key di jadwal_pelajaran
            'id_mapel',       // Foreign key di mata_pelajaran
            'jadwal_id',      // Local key di materi
            'mapel_id'        // Local key di jadwal_pelajaran
        );
    }

    /**
     * Relasi langsung ke Guru melalui Jadwal
     */
    public function guru()
    {
        return $this->hasOneThrough(
            Guru::class,
            JadwalPelajaran::class,
            'id_jadwal',
            'id_guru',
            'jadwal_id',
            'guru_id'
        );
    }

    /**
     * Relasi langsung ke Kelas melalui Jadwal
     */
    public function kelas()
    {
        return $this->hasOneThrough(
            Kelas::class,
            JadwalPelajaran::class,
            'id_jadwal',
            'id_kelas',
            'jadwal_id',
            'kelas_id'
        );
    }
}
