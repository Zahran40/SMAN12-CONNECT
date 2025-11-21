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

    // Relasi ke Kelas (deprecated - gunakan kelasAktif() untuk data aktual)
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
    }

    /**
     * Relasi Many-to-Many ke Kelas melalui siswa_kelas
     */
    public function kelasHistory()
    {
        return $this->belongsToMany(
            Kelas::class,
            'siswa_kelas',
            'siswa_id',
            'kelas_id',
            'id_siswa',
            'id_kelas'
        )->withPivot('tahun_ajaran_id', 'status', 'tanggal_masuk', 'tanggal_keluar');
    }

    /**
     * Kelas aktif siswa saat ini
     */
    public function kelasAktif()
    {
        return $this->belongsToMany(
            Kelas::class,
            'siswa_kelas',
            'siswa_id',
            'kelas_id',
            'id_siswa',
            'id_kelas'
        )->wherePivot('status', 'Aktif')
         ->withPivot('tahun_ajaran_id', 'status', 'tanggal_masuk');
    }

    /**
     * Relasi ke SiswaKelas
     */
    public function siswaKelas()
    {
        return $this->hasMany(SiswaKelas::class, 'siswa_id', 'id_siswa');
    }
}
