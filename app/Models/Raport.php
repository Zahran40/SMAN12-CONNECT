<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'deskripsi'
    ];

    protected $casts = [
        'nilai_tugas' => 'decimal:2',
        'nilai_uts' => 'decimal:2',
        'nilai_uas' => 'decimal:2',
    ];
    
    // Append computed attributes
    protected $appends = ['nilai_akhir', 'nilai_huruf'];

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
     * Auto-calculate nilai tugas menggunakan stored procedure
     * Hitung rata-rata dari semua tugas yang sudah dinilai untuk semester tertentu
     */
    public function calculateNilaiTugas()
    {
        // Panggil stored procedure dengan semester
        $result = DB::select('CALL sp_calculate_average_tugas(?, ?, ?, @average)', [
            $this->siswa_id,
            $this->mapel_id,
            $this->semester  // Tambahkan parameter semester
        ]);
        
        // Ambil hasil dari OUT parameter
        $average = DB::select('SELECT @average as average')[0]->average;
        
        // Update nilai_tugas dengan hasil dari stored procedure
        $this->nilai_tugas = $average;
        
        return $average;
    }

    /**
     * Accessor untuk nilai_akhir (computed attribute)
     * Menghitung nilai akhir menggunakan database function
     * Formula: 30% Tugas + 30% UTS + 40% UAS
     */
    public function getNilaiAkhirAttribute()
    {
        if (isset($this->attributes['nilai_akhir']) && $this->attributes['nilai_akhir'] !== null) {
            return (float) $this->attributes['nilai_akhir'];
        }

        try {
            $result = DB::select('SELECT fn_calculate_nilai_akhir(?, ?, ?) as nilai_akhir', [
                $this->nilai_tugas ?? 0,
                $this->nilai_uts ?? 0,
                $this->nilai_uas ?? 0
            ]);
            return (float) ($result[0]->nilai_akhir ?? 0);
        } catch (\Throwable $e) {
            $tugas = (float) ($this->nilai_tugas ?? 0);
            $uts = (float) ($this->nilai_uts ?? 0);
            $uas = (float) ($this->nilai_uas ?? 0);
            return round(($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4), 2);
        }
    }

    public function getNilaiHurufAttribute()
    {
        if (isset($this->attributes['nilai_huruf']) && $this->attributes['nilai_huruf'] !== null) {
            return $this->attributes['nilai_huruf'];
        }

        $nilaiAkhir = $this->nilai_akhir;
        if (!$nilaiAkhir) return '-';

        try {
            $result = DB::select('SELECT fn_convert_grade_letter(?) as grade', [$nilaiAkhir]);
            return $result[0]->grade ?? '-';
        } catch (\Throwable $e) {
            if ($nilaiAkhir >= 85) return 'A';
            if ($nilaiAkhir >= 75) return 'B';
            if ($nilaiAkhir >= 65) return 'C';
            if ($nilaiAkhir >= 50) return 'D';
            return 'E';
        }
    }
    
    /**
     * Alias untuk backward compatibility
     * @deprecated Use getNilaiAkhirAttribute() accessor instead
     */
    public function hitungNilaiAkhir()
    {
        return $this->nilai_akhir;
    }

    /**
     * Accessor untuk grade (alias dari nilai_huruf)
     * @deprecated Use getNilaiHurufAttribute() instead
     */
    public function getGradeAttribute()
    {
        return $this->nilai_huruf;
    }
}
