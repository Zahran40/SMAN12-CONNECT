<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetSekolah extends Model
{
    use HasFactory;

    protected $table = 'target_sekolah';
    protected $primaryKey = 'id_target';

    protected $fillable = [
        'tahun_ajaran_id',
        'nama_target',
        'deskripsi',
        'kategori',
        'indikator',
        'target_nilai',
        'nilai_saat_ini',
        'satuan',
        'tanggal_mulai',
        'tanggal_target',
        'status',
        'persentase_pencapaian',
        'dibuat_oleh',
        'catatan',
    ];

    protected $casts = [
        'target_nilai' => 'decimal:2',
        'nilai_saat_ini' => 'decimal:2',
        'persentase_pencapaian' => 'integer',
        'tanggal_mulai' => 'date',
        'tanggal_target' => 'date',
    ];

    // Relationships
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id', 'id_tahun_ajaran');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
