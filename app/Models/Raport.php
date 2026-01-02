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
        // Gunakan function dari database untuk menghitung nilai akhir
        $result = DB::select('SELECT fn_calculate_nilai_akhir(?, ?, ?) as nilai_akhir', [
            $this->nilai_tugas,
            $this->nilai_uts,
            $this->nilai_uas
        ]);
        
        return $result[0]->nilai_akhir ?? 0;
    }

    /**
     * Accessor untuk nilai_huruf (computed attribute)
     * Mengkonversi nilai akhir ke huruf (A/B/C/D/E) menggunakan database function
     */
    public function getNilaiHurufAttribute()
    {
        $nilaiAkhir = $this->nilai_akhir;
        
        if (!$nilaiAkhir) return '-';
        
        // Gunakan function dari database untuk konversi ke huruf
        $result = DB::select('SELECT fn_convert_grade_letter(?) as grade', [$nilaiAkhir]);
        
        return $result[0]->grade ?? '-';
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
