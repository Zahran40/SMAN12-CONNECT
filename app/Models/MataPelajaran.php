<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';
    protected $primaryKey = 'id_mapel';
    public $timestamps = false;

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'kategori',
        'deskripsi',
    ];

    /**
     * Relasi ke Jadwal Pelajaran (one to many)
     */
    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class, 'mapel_id', 'id_mapel');
    }

    /**
     * Relasi ke Nilai (one to many)
     */
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'mapel_id', 'id_mapel');
    }
}
