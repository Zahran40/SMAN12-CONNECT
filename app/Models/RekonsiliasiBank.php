<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekonsiliasiBank extends Model
{
    use HasFactory;

    protected $table = 'rekonsiliasi_bank';
    protected $primaryKey = 'id_rekonsiliasi';

    protected $fillable = [
        'tanggal_rekonsiliasi',
        'nama_bank',
        'nomor_rekening',
        'saldo_awal',
        'total_pemasukan',
        'total_pengeluaran',
        'saldo_akhir',
        'saldo_sistem',
        'selisih',
        'status',
        'catatan',
        'file_statement',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_rekonsiliasi' => 'date',
        'saldo_awal' => 'decimal:2',
        'total_pemasukan' => 'decimal:2',
        'total_pengeluaran' => 'decimal:2',
        'saldo_akhir' => 'decimal:2',
        'saldo_sistem' => 'decimal:2',
        'selisih' => 'decimal:2',
    ];

    // Relationships
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function items()
    {
        return $this->hasMany(RekonsiliasiItem::class, 'rekonsiliasi_id', 'id_rekonsiliasi');
    }
}
