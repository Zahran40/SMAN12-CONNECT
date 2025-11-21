<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    protected $primaryKey = 'id_log';
    public $timestamps = false; // Kita hanya punya created_at (waktu)

    const CREATED_AT = 'waktu';
    const UPDATED_AT = null;

    protected $fillable = [
        'jenis_aktivitas',
        'deskripsi',
        'user_id',
        'role',
        'nama_tabel',
        'id_referensi',
        'aksi',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'waktu' => 'datetime',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope untuk filter berdasarkan jenis aktivitas
     */
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis_aktivitas', $jenis);
    }

    /**
     * Scope untuk filter berdasarkan role
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeTanggal($query, $dari, $sampai)
    {
        return $query->whereBetween('waktu', [$dari, $sampai]);
    }
}
