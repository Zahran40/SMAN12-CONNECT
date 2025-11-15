<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';
    protected $primaryKey = 'id_guru';
    
    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'alamat',
        'no_telepon',
        'email',
        'agama',
        'golongan_darah',
        'foto_profil',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Jadwal Pelajaran
    // public function jadwalPelajaran()
    // {
    //     return $this->hasMany(JadwalPelajaran::class, 'guru_id', 'id_guru');
    // }
}
