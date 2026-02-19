<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekonsiliasiItem extends Model
{
    use HasFactory;

    protected $table = 'detail_rekonsiliasi';

    protected $fillable = [
        'rekonsiliasi_id',
        'pembayaran_id',
        'tanggal_transaksi',
        'deskripsi',
        'debit',
        'kredit',
        'referensi',
        'status_match',
        'catatan',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'debit' => 'decimal:2',
        'kredit' => 'decimal:2',
    ];

    // Relationships
    public function rekonsiliasi()
    {
        return $this->belongsTo(RekonsiliasiBank::class, 'rekonsiliasi_id', 'id_rekonsiliasi');
    }

    public function pembayaran()
    {
        return $this->belongsTo(PembayaranSpp::class, 'pembayaran_id', 'id_pembayaran');
    }
}
