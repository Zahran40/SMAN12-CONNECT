<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Raport extends Model
{
    protected $table = 'nilai';
    protected $primaryKey = 'id_nilai';
    public $timestamps = false; // Nonaktifkan timestamps
    
    protected $fillable = [
        'tahun_ajaran_id',
        'siswa_id',
        'mapel_id',
        'semester',
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
        'nilai_akhir',
        'deskripsi'
    ];

    protected $casts = [
        'nilai_tugas' => 'decimal:2',
        'nilai_uts' => 'decimal:2',
        'nilai_uas' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
    ];

    /**
     * Relasi ke Siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id_siswa');
    }

    /**
     * Relasi ke Mata Pelajaran
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id', 'id_mapel');
    }

    /**
     * Relasi ke Tahun Ajaran
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id', 'id_tahun_ajaran');
    }

    /**
     * Hitung nilai akhir berdasarkan bobot:
     * Tugas 30%, UTS 30%, UAS 40%
     */
    public function hitungNilaiAkhir()
    {
        if ($this->nilai_tugas !== null && $this->nilai_uts !== null && $this->nilai_uas !== null) {
            $this->nilai_akhir = ($this->nilai_tugas * 0.3) + ($this->nilai_uts * 0.3) + ($this->nilai_uas * 0.4);
            return $this->nilai_akhir;
        }
        return null;
    }

    /**
     * Tentukan grade berdasarkan nilai akhir
     */
    public function getGradeAttribute()
    {
        if (!$this->nilai_akhir) return '-';
        
        $nilai = $this->nilai_akhir;
        if ($nilai >= 90) return 'A';
        if ($nilai >= 80) return 'B';
        if ($nilai >= 70) return 'C';
        if ($nilai >= 60) return 'D';
        return 'E';
    }
}
