<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengumumanDibaca extends Model
{
    use HasFactory;

    protected $table = 'pengumuman_dibaca';
    public $timestamps = false;

    protected $fillable = [
        'pengumuman_id',
        'user_id',
        'dibaca_pada',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'dibaca_pada' => 'datetime',
    ];

    // Relationships
    public function pengumuman()
    {
        return $this->belongsTo(Pengumuman::class, 'pengumuman_id', 'id_pengumuman');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
