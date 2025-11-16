<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertemuan extends Model
{
    protected $table = 'pertemuan';
    protected $primaryKey = 'id_pertemuan';
    public $timestamps = false; // Tabel pertemuan tidak punya created_at/updated_at

    protected $fillable = [
        'jadwal_id',
        'nomor_pertemuan',
        'tanggal_pertemuan',
        'waktu_mulai',
        'waktu_selesai',
        'topik_bahasan',
        'status_sesi',
        'tanggal_absen_dibuka',
        'tanggal_absen_ditutup',
        'jam_absen_buka',
        'jam_absen_tutup',
        'waktu_absen_dibuka',
        'waktu_absen_ditutup',
        'is_submitted',
        'submitted_at',
        'submitted_by',
    ];

    protected $casts = [
        'tanggal_pertemuan' => 'date',
        'nomor_pertemuan' => 'integer',
        'tanggal_absen_dibuka' => 'date',
        'tanggal_absen_ditutup' => 'date',
        'waktu_absen_dibuka' => 'datetime',
        'waktu_absen_ditutup' => 'datetime',
        'is_submitted' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    // Accessor for compatibility with views using pertemuan_ke
    public function getPertemuanKeAttribute()
    {
        return $this->nomor_pertemuan;
    }

    /**
     * Relasi ke Jadwal Pelajaran
     */
    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id', 'id_jadwal');
    }

    /**
     * Relasi ke Materi (one to many)
     */
    public function materi()
    {
        return $this->hasMany(Materi::class, 'pertemuan_id', 'id_pertemuan');
    }

    /**
     * Relasi ke Tugas (one to many)
     */
    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'pertemuan_id', 'id_pertemuan');
    }

    /**
     * Relasi ke Detail Absensi (one to many)
     */
    public function detailAbsensi()
    {
        return $this->hasMany(DetailAbsensi::class, 'pertemuan_id', 'id_pertemuan');
    }

    /**
     * Relasi ke User yang submit absensi
     */
    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by', 'id');
    }

    /**
     * Cek apakah waktu absensi sedang terbuka
     */
    public function isAbsensiOpen()
    {
        if (!$this->waktu_absen_dibuka || !$this->waktu_absen_ditutup) {
            return false;
        }

        $now = now();
        $waktuBuka = \Carbon\Carbon::parse($this->waktu_absen_dibuka);
        $waktuTutup = \Carbon\Carbon::parse($this->waktu_absen_ditutup);

        return $now->greaterThanOrEqualTo($waktuBuka) && $now->lessThanOrEqualTo($waktuTutup);
    }

    /**
     * Cek apakah absensi bisa diedit
     * @param \App\Models\User|null $user
     * @return bool
     */
    public function canEditAbsensi($user = null)
    {
        // Jika belum di-submit, semua bisa edit
        if (!$this->is_submitted) {
            return true;
        }

        // Jika sudah di-submit, hanya admin yang bisa edit
        if ($user && $user->role === 'admin') {
            return true;
        }

        return false;
    }
}
