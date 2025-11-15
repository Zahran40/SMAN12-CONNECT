<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';
    protected $primaryKey = 'id_tugas';
    public $timestamps = false;

    protected $fillable = [
        'jadwal_id',
        'pertemuan_id',
        'judul_tugas',
        'deskripsi_tugas',
        'deadline',
        'file_tugas',
        'tgl_upload',
        'jam_buka',
        'jam_tutup',
    ];

    protected $casts = [
        'tgl_upload' => 'datetime',
        'deadline' => 'date',
    ];

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
     * Relasi ke Detail Tugas (one to many)
     */
    public function detailTugas()
    {
        return $this->hasMany(DetailTugas::class, 'tugas_id', 'id_tugas');
    }
}
