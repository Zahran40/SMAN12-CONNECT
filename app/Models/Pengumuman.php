<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';
    protected $primaryKey = 'id_pengumuman';
    public $timestamps = false;

    protected $fillable = [
        'judul',
        'isi_pengumuman',
        'tgl_publikasi',
        'hari',
        'target_role',
        'author_id',
        'file_lampiran',
        'status',
        'tanggal_dibuat',
    ];

    protected $casts = [
        'tgl_publikasi' => 'date',
        'tanggal_dibuat' => 'datetime',
    ];

    // Accessor untuk isi (alias dari isi_pengumuman)
    public function getIsiAttribute()
    {
        return $this->attributes['isi_pengumuman'] ?? null;
    }

    // Mutator untuk isi
    public function setIsiAttribute($value)
    {
        $this->attributes['isi_pengumuman'] = $value;
    }
}
