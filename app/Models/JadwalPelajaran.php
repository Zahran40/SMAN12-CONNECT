<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPelajaran extends Model
{
    protected $table = 'jadwal_pelajaran';
    protected $primaryKey = 'id_jadwal'; // Primary key di database: id_jadwal
    public $timestamps = false;

    protected $fillable = [
        'tahun_ajaran_id',
        'kelas_id',
        'mapel_id',
        'guru_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    /**
     * Relasi ke Tahun Ajaran
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id', 'id_tahun_ajaran');
    }

    /**
     * Relasi ke Kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
    }

    /**
     * Relasi ke Mata Pelajaran
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id', 'id_mapel');
    }

    /**
     * Relasi ke Guru
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'id_guru');
    }

    /**
     * Relasi ke Materi (one to many)
     */
    public function materi()
    {
        return $this->hasMany(Materi::class, 'jadwal_id', 'id_jadwal');
    }

    /**
     * Relasi ke Pertemuan (one to many)
     */
    public function pertemuan()
    {
        return $this->hasMany(Pertemuan::class, 'jadwal_id', 'id_jadwal');
    }
}
