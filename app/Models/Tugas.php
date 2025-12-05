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
        'semester',
        'judul_tugas',
        'deskripsi_tugas',
        'file_path',
        'waktu_dibuka',
        'waktu_ditutup',
        'deadline',
    ];

    protected $casts = [
        'waktu_dibuka' => 'datetime',
        'waktu_ditutup' => 'datetime',
        'deadline' => 'datetime',
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

    /**
     * Accessor untuk waktu_ditutup
     */
    public function getWaktuDitutupAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        return \Carbon\Carbon::parse($value);
    }

    /**
     * Accessor untuk waktu_dibuka
     */
    public function getWaktuDibukaAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        return \Carbon\Carbon::parse($value);
    }
    
    /**
     * Accessor untuk deskripsi_tugas (backward compatibility)
     */
    public function getDeskripsiAttribute()
    {
        return $this->attributes['deskripsi_tugas'] ?? null;
    }
    
    /**
     * Mutator untuk deskripsi (backward compatibility)
     */
    public function setDeskripsiAttribute($value)
    {
        $this->attributes['deskripsi_tugas'] = $value;
    }
    
    /**
     * Accessor untuk deskripsi_tugas
     */
    public function getDeskripsiTugasAttribute()
    {
        return $this->attributes['deskripsi_tugas'] ?? null;
    }
    
    /**
     * Mutator untuk deskripsi_tugas
     */
    public function setDeskripsiTugasAttribute($value)
    {
        $this->attributes['deskripsi_tugas'] = $value;
    }
}
