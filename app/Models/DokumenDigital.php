<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DokumenDigital extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dokumen_digital';
    protected $primaryKey = 'id_dokumen';

    protected $fillable = [
        'nomor_dokumen',
        'judul',
        'jenis_dokumen',
        'deskripsi',
        'file_path',
        'dibuat_oleh',
        'status',
        'menunggu_ttd_dari',
        'ditandatangani_oleh',
        'tanggal_ttd',
        'signature_path',
        'signature_hash',
        'catatan_penolakan',
        'tanggal_dokumen',
        'tanggal_berlaku',
        'tanggal_kadaluarsa',
        'tags',
    ];

    protected $casts = [
        'tanggal_ttd' => 'datetime',
        'tanggal_dokumen' => 'date',
        'tanggal_berlaku' => 'date',
        'tanggal_kadaluarsa' => 'date',
    ];

    // Relationships
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function penandatangan()
    {
        return $this->belongsTo(User::class, 'ditandatangani_oleh');
    }

    public function penunggutTd()
    {
        return $this->belongsTo(User::class, 'menunggu_ttd_dari');
    }
}
