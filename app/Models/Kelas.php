<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    public $timestamps = false;

    protected $fillable = [
        'tahun_ajaran_id',
        'nama_kelas',
        'tingkat',
        'jurusan',
        'wali_kelas_id',
    ];

    /**
     * Relasi ke Tahun Ajaran
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id', 'id_tahun_ajaran');
    }

    /**
     * Relasi ke Wali Kelas (Guru)
     */
    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id', 'id_guru');
    }

    /**
     * Relasi ke Siswa (one to many)
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id', 'id_kelas');
    }

    /**
     * Relasi ke Jadwal Pelajaran (one to many)
     */
    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class, 'kelas_id', 'id_kelas');
    }
}
