<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'nama_lengkap',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'alamat',
        'no_telepon',
        'email',
        'agama',
        'golongan_darah',
        'kelas_id',
        'foto_profil',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relasi ke Nilai/Raport
     */
    public function nilai()
    {
        return $this->hasMany(Raport::class, 'siswa_id', 'id_siswa');
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
    }

    // Relasi ke Tahun Ajaran (melalui kelas)
    public function tahunAjaran()
    {
        return $this->hasOneThrough(
            TahunAjaran::class,
            Kelas::class,
            'id_kelas',
            'id_tahun_ajaran',
            'kelas_id',
            'tahun_ajaran_id'
        );
    }
}
