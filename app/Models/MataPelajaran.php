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
        'jam_mulai',
        'jam_selesai',
    ];

    /**
     * Relasi ke Jadwal Pelajaran (one to many)
     */
    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class, 'mapel_id', 'id_mapel');
    }

    /**
     * Alias untuk jadwalPelajaran
     */
    public function jadwal()
    {
        return $this->jadwalPelajaran();
    }

    /**
     * Relasi ke Guru melalui jadwal_pelajaran
     * Ini mengembalikan guru-guru yang mengajar mapel ini
     */
    public function guru()
    {
        return $this->belongsToMany(
            Guru::class,
            'jadwal_pelajaran',
            'mapel_id',
            'guru_id',
            'id_mapel',
            'id_guru'
        )->distinct();
    }

    /**
     * Relasi ke Nilai (one to many)
     */
    // public function nilai()
    // {
    //     return $this->hasMany(Nilai::class, 'mapel_id', 'id_mapel');
    // }
}
