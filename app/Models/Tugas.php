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
        'file_path',
        'tanggal_dibuka',
        'tanggal_ditutup',
        'tanggal_deadline',
        'waktu_dibuka',
        'waktu_ditutup',
        'jam_buka',
        'jam_tutup',
    ];

    protected $casts = [
        'tanggal_dibuka' => 'date',
        'tanggal_ditutup' => 'date',
        'tanggal_deadline' => 'date',
        'waktu_dibuka' => 'datetime',
        'waktu_ditutup' => 'datetime',
    ];

    /**
     * Relasi ke Jadwal Pelajaran
     */
    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id', 'id_jadwal');
    }

    /**
     * Alias untuk relasi jadwal (untuk konsistensi penamaan)
     */
    public function jadwalPelajaran()
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
