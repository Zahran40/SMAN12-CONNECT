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
        'nilai_akhir',
        'nilai_huruf',
        'is_locked',
        'deskripsi'
    ];

    protected $casts = [
        'nilai_tugas' => 'decimal:2',
        'nilai_uts' => 'decimal:2',
        'nilai_uas' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
        'is_locked' => 'boolean',
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
     * Hitung nilai akhir berdasarkan bobot:
     * Tugas 30%, UTS 30%, UAS 40%
     */
    public function hitungNilaiAkhir()
    {
        if ($this->nilai_tugas !== null && $this->nilai_uts !== null && $this->nilai_uas !== null) {
            $nilaiAkhir = ($this->nilai_tugas * 0.3) + ($this->nilai_uts * 0.3) + ($this->nilai_uas * 0.4);
            $this->nilai_akhir = $nilaiAkhir;
            
            // Auto-calculate nilai huruf menggunakan function
            $this->nilai_huruf = DB::select('SELECT fn_convert_grade_letter(?) as grade', [
                $this->nilai_akhir
            ])[0]->grade;
            
            return $this->nilai_akhir;
        }
        return null;
    }

    /**
     * Tentukan grade berdasarkan nilai akhir menggunakan function
     */
    public function getGradeAttribute()
    {
        if (!$this->nilai_akhir) return '-';
        
        // Gunakan function dari database
        $grade = DB::select('SELECT fn_convert_grade_letter(?) as grade', [
            $this->nilai_akhir
        ])[0]->grade;
        
        return $grade;
    }
}
