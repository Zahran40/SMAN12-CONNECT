<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    use HasFactory;

    protected $table = 'perizinan';
    protected $primaryKey = 'id_izin';

    // Migration only defines created_at
    const UPDATED_AT = null;

    protected $fillable = [
        'siswa_id',
        'orang_tua_id',
        'tahun_ajaran_id',
        'jenis_izin',
        'tgl_mulai',
        'tgl_selesai',
        'keterangan',
        'file_bukti',
        'status',
        'approval_by',
        'approval_date',
    ];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
        'approval_date' => 'datetime',
    ];

    // Relationships
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id_siswa');
    }

    public function orangTua()
    {
        return $this->belongsTo(User::class, 'orang_tua_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id', 'id_tahun_ajaran');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approval_by');
    }
}
