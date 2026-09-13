<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReminder extends Model
{
    use HasFactory;

    protected $table = 'payment_reminder';
    protected $primaryKey = 'id_reminder';

    protected $fillable = [
        'pembayaran_id',
        'siswa_id',
        'metode',
        'tujuan',
        'pesan',
        'status',
        'waktu_kirim',
        'waktu_dibaca',
        'percobaan_ke',
        'error_message',
        'external_id',
        'is_auto',
        'dikirim_oleh',
    ];

    protected $casts = [
        'waktu_kirim' => 'datetime',
        'waktu_dibaca' => 'datetime',
        'is_auto' => 'boolean',
        'percobaan_ke' => 'integer',
    ];

    // Relationships
    public function pembayaran()
    {
        return $this->belongsTo(PembayaranSpp::class, 'pembayaran_id', 'id_pembayaran');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id_siswa');
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'dikirim_oleh');
    }
}
