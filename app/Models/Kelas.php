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
     * Relasi ke Siswa (deprecated - gunakan siswaAktif() untuk data aktual)
     * Ini tetap ada untuk backward compatibility
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id', 'id_kelas');
    }

    /**
     * Relasi Many-to-Many ke Siswa melalui siswa_kelas
     */
    public function siswaHistory()
    {
        return $this->belongsToMany(
            Siswa::class,
            'siswa_kelas',
            'kelas_id',
            'siswa_id',
            'id_kelas',
            'id_siswa'
        )->withPivot('tahun_ajaran_id', 'status', 'tanggal_masuk', 'tanggal_keluar');
    }

    /**
     * Siswa aktif di kelas ini
     */
    public function siswaAktif()
    {
        return $this->belongsToMany(
            Siswa::class,
            'siswa_kelas',
            'kelas_id',
            'siswa_id',
            'id_kelas',
            'id_siswa'
        )->wherePivot('status', 'Aktif')
         ->withPivot('tahun_ajaran_id', 'status', 'tanggal_masuk');
    }

    /**
     * Relasi ke SiswaKelas
     */
    public function siswaKelas()
    {
        return $this->hasMany(SiswaKelas::class, 'kelas_id', 'id_kelas');
    }

    /**
     * Relasi ke Jadwal Pelajaran (one to many)
     */
    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class, 'kelas_id', 'id_kelas');
    }
}
