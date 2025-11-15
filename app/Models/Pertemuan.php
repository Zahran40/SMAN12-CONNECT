<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertemuan extends Model
{
    protected $table = 'pertemuan';
    protected $primaryKey = 'id_pertemuan';
    public $timestamps = true;

    protected $fillable = [
        'jadwal_id',
        'nomor_pertemuan',
        'tanggal_pertemuan',
        'waktu_mulai',
        'waktu_selesai',
        'topik_bahasan',
        'status_sesi',
    ];

    protected $casts = [
        'tanggal_pertemuan' => 'date',
        'nomor_pertemuan' => 'integer',
    ];

    // Accessor for compatibility with views using pertemuan_ke
    public function getPertemuanKeAttribute()
    {
        return $this->nomor_pertemuan;
    }

    /**
     * Relasi ke Jadwal Pelajaran
     */
    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id', 'id_jadwal');
    }

    /**
     * Relasi ke Materi (one to many)
     */
    public function materi()
    {
        return $this->hasMany(Materi::class, 'pertemuan_id', 'id_pertemuan');
    }

    /**
     * Relasi ke Tugas (one to many)
     */
    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'pertemuan_id', 'id_pertemuan');
    }

    /**
     * Relasi ke Detail Absensi (one to many)
     */
    public function detailAbsensi()
    {
        return $this->hasMany(DetailAbsensi::class, 'pertemuan_id', 'id_pertemuan');
    }
}
