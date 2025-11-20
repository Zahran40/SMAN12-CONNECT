<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembayaranSpp extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_spp';
    protected $primaryKey = 'id_pembayaran';
    
    // Custom timestamps - hanya created_at
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'siswa_id',
        'tahun_ajaran_id',
        'nama_tagihan',
        'bulan',
        'tahun',
        'jumlah_bayar',
        'tgl_bayar',
        'status',
        'metode_pembayaran',
        'nomor_va',
        'bukti_pembayaran',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_payment_type',
        'midtrans_transaction_status',
        'midtrans_response',
        'midtrans_paid_at',
    ];

    protected $casts = [
        'tgl_bayar' => 'date',
        'midtrans_paid_at' => 'datetime',
        'jumlah_bayar' => 'decimal:2',
    ];

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id_siswa');
    }

    // Relasi ke Tahun Ajaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id', 'id_tahun_ajaran');
    }
}
