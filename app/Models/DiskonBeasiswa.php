<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiskonBeasiswa extends Model
{
    use HasFactory;

    protected $table = 'diskon_beasiswa';
    protected $primaryKey = 'id_diskon';

    protected $fillable = [
        'nama_program',
        'deskripsi',
        'jenis',
        'tipe_potongan',
        'nilai_potongan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'syarat',
        'dokumen_persyaratan',
        'dibuat_oleh',
    ];

    protected $casts = [
        'nilai_potongan' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Relationships
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    // Many-to-many with siswa via siswa_diskon pivot table
    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'siswa_diskon', 'diskon_id', 'siswa_id', 'id_diskon', 'id_siswa')
            ->withPivot(['tahun_ajaran_id', 'tanggal_diberikan', 'catatan', 'dokumen_pendukung', 'status_verifikasi', 'diverifikasi_oleh', 'tanggal_verifikasi', 'alasan_penolakan'])
            ->withTimestamps();
    }
}
