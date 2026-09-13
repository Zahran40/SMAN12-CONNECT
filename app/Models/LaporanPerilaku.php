<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPerilaku extends Model
{
    use HasFactory;

    protected $table = 'laporan_perilaku';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'siswa_id',
        'pelapor_id',
        'tahun_ajaran_id',
        'jenis',
        'judul',
        'deskripsi',
        'tanggal_kejadian',
        'lokasi',
        'tingkat_severity',
        'poin',
        'bukti_foto',
        'tindak_lanjut',
        'status',
        'notifikasi_ortu',
        'dibaca_ortu_at',
    ];

    protected $casts = [
        'tanggal_kejadian' => 'date',
        'poin' => 'integer',
        'notifikasi_ortu' => 'boolean',
        'dibaca_ortu_at' => 'datetime',
    ];

    // Relationships
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id_siswa');
    }

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id', 'id_tahun_ajaran');
    }
}
