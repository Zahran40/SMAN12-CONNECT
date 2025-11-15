<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';
    protected $primaryKey = 'id_tahun_ajaran';
    public $timestamps = false;

    protected $fillable = [
        'tahun_mulai',
        'tahun_selesai',
        'semester',
        'status',
    ];

    /**
     * Relasi ke Jadwal Pelajaran (one to many)
     */
    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class, 'tahun_ajaran_id', 'id_tahun_ajaran');
    }

    /**
     * Relasi ke Kelas (one to many)
     */
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'tahun_ajaran_id', 'id_tahun_ajaran');
    }
}
