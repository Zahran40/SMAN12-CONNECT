<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTugas extends Model
{
    protected $table = 'detail_tugas';
    protected $primaryKey = 'id_detail_tugas';
    public $timestamps = false;

    protected $fillable = [
        'tugas_id',
        'siswa_id',
        'file_path',
        'tgl_kumpul',
        'teks_jawaban',
        'nilai',
        'komentar_guru',
    ];

    protected $casts = [
        'tgl_kumpul' => 'datetime',
        'nilai' => 'decimal:2',
    ];

    /**
     * Relasi ke Tugas
     */
    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id', 'id_tugas');
    }

    /**
     * Relasi ke Siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id_siswa');
    }
}
