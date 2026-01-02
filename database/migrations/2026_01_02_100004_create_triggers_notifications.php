<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Triggers untuk auto notification & logging
     * CATATAN: Beberapa trigger dikomentari karena kompleksitas
     * Bisa diaktifkan nanti setelah struktur tabel final
     */
    public function up(): void
    {
        // 1. TRIGGER AFTER PEMBAYARAN - Send notification ke orang tua
        DB::unprepared("
            DROP TRIGGER IF EXISTS after_pembayaran_insert;
        ");
        
        DB::unprepared("
            CREATE TRIGGER after_pembayaran_insert
            AFTER INSERT ON pembayaran_spp
            FOR EACH ROW
            BEGIN
                -- Insert notification untuk siswa
                INSERT INTO notifikasi (user_id, tipe, judul, pesan, link, created_at)
                SELECT 
                    u.id,
                    'Pembayaran',
                    'Tagihan SPP Baru',
                    CONCAT('Tagihan SPP bulan ', NEW.bulan, '/', NEW.tahun, ' sebesar Rp ', FORMAT(NEW.jumlah_bayar, 0), ' telah dibuat.'),
                    '/siswa/pembayaran',
                    NOW()
                FROM users u
                JOIN siswa s ON u.reference_id = s.id_siswa
                WHERE s.id_siswa = NEW.siswa_id
                AND u.role = 'siswa';
            END
        ");

        // 2. TRIGGER AFTER PEMBAYARAN UPDATE - Notify when payment status changes
        DB::unprepared("
            DROP TRIGGER IF EXISTS after_pembayaran_update;
        ");
        
        DB::unprepared("
            CREATE TRIGGER after_pembayaran_update
            AFTER UPDATE ON pembayaran_spp
            FOR EACH ROW
            BEGIN
                IF OLD.status = 'Belum Lunas' AND NEW.status = 'Lunas' THEN
                    -- Notification untuk siswa
                    INSERT INTO notifikasi (user_id, tipe, judul, pesan, link, created_at)
                    SELECT 
                        u.id,
                        'Pembayaran',
                        'Pembayaran Berhasil',
                        CONCAT('Pembayaran SPP bulan ', NEW.bulan, '/', NEW.tahun, ' sebesar Rp ', FORMAT(NEW.jumlah_bayar, 0), ' telah lunas.'),
                        '/siswa/pembayaran',
                        NOW()
                    FROM users u
                    JOIN siswa s ON u.reference_id = s.id_siswa
                    WHERE s.id_siswa = NEW.siswa_id
                    AND u.role = 'siswa';
                END IF;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS after_pembayaran_update");
        DB::unprepared("DROP TRIGGER IF EXISTS after_pembayaran_insert");
    }
};
