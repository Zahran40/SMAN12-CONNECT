<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluasiKinerjaGuru extends Model
{
    use HasFactory;

    protected $table = 'evaluasi_kinerja_guru';
    protected $primaryKey = 'id_evaluasi';

    protected $fillable = [
        'guru_id',
        'tahun_ajaran_id',
        'periode_mulai',
        'periode_selesai',
        'evaluator_id',
        'ketepatan_waktu_mengajar',
        'kelengkapan_materi',
        'interaksi_siswa',
        'kualitas_pembelajaran',
        'administrasi_kelas',
        'pengembangan_diri',
        'skor_total',
        'kategori',
        'catatan_positif',
        'area_perbaikan',
        'rekomendasi',
        'status',
        'tanggal_evaluasi',
    ];

    protected $casts = [
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
        'skor_total' => 'decimal:2',
        'tanggal_evaluasi' => 'datetime',
    ];

    // Relationships
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'id_guru');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id', 'id_tahun_ajaran');
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}
