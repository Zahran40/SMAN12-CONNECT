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
        'tanggal_dibuka',
        'tanggal_ditutup',
        'deadline',
        'jam_buka',
        'jam_tutup',
    ];

    protected $casts = [
        'tanggal_dibuka' => 'date',
        'tanggal_ditutup' => 'date',
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
     * Accessor untuk waktu_ditutup (tanggal_ditutup + jam_tutup)
     */
    public function getWaktuDitutupAttribute()
    {
        if (!$this->tanggal_ditutup) {
            return null;
        }
        
        $tanggalDitutup = \Carbon\Carbon::parse($this->tanggal_ditutup);
        
        if ($this->jam_tutup) {
            $jamTutupParts = explode(':', $this->jam_tutup);
            $tanggalDitutup->setTime(
                (int)$jamTutupParts[0], 
                (int)$jamTutupParts[1], 
                isset($jamTutupParts[2]) ? (int)$jamTutupParts[2] : 0
            );
        }
        
        return $tanggalDitutup;
    }

    /**
     * Accessor untuk waktu_dibuka (tanggal_dibuka + jam_buka)
     */
    public function getWaktuDibukaAttribute()
    {
        if (!$this->tanggal_dibuka) {
            return null;
        }
        
        $tanggalDibuka = \Carbon\Carbon::parse($this->tanggal_dibuka);
        
        if ($this->jam_buka) {
            $jamBukaParts = explode(':', $this->jam_buka);
            $tanggalDibuka->setTime(
                (int)$jamBukaParts[0], 
                (int)$jamBukaParts[1], 
                isset($jamBukaParts[2]) ? (int)$jamBukaParts[2] : 0
            );
        }
        
        return $tanggalDibuka;
    }
}
