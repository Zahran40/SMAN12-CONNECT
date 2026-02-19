<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundPembayaran extends Model
{
    use HasFactory;

    protected $table = 'refund_pembayaran';
    protected $primaryKey = 'id_refund';

    protected $fillable = [
        'pembayaran_id',
        'siswa_id',
        'jumlah_refund',
        'alasan',
        'jenis',
        'metode_refund',
        'nomor_rekening',
        'nama_bank',
        'nama_pemilik_rekening',
        'status',
        'diajukan_oleh',
        'disetujui_oleh',
        'tanggal_pengajuan',
        'tanggal_persetujuan',
        'tanggal_refund',
        'catatan_bendahara',
        'bukti_transfer',
    ];

    protected $casts = [
        'jumlah_refund' => 'decimal:2',
        'tanggal_pengajuan' => 'datetime',
        'tanggal_persetujuan' => 'datetime',
        'tanggal_refund' => 'datetime',
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

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
