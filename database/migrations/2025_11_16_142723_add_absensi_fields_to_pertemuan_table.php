<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pertemuan', function (Blueprint $table) {
            // Kolom tanggal dan waktu untuk absensi (pattern sama seperti tugas)
            $table->date('tanggal_absen_dibuka')->nullable()->after('status_sesi');
            $table->date('tanggal_absen_ditutup')->nullable()->after('tanggal_absen_dibuka');
            $table->time('jam_absen_buka')->nullable()->after('tanggal_absen_ditutup');
            $table->time('jam_absen_tutup')->nullable()->after('jam_absen_buka');
            
            // Kolom waktu gabungan untuk query yang lebih mudah
            $table->dateTime('waktu_absen_dibuka')->nullable()->after('jam_absen_tutup');
            $table->dateTime('waktu_absen_ditutup')->nullable()->after('waktu_absen_dibuka');
            
            // Kolom untuk status submit oleh guru
            $table->boolean('is_submitted')->default(false)->after('waktu_absen_ditutup');
            $table->dateTime('submitted_at')->nullable()->after('is_submitted');
            $table->bigInteger('submitted_by')->nullable()->after('submitted_at');
            
            // Foreign key untuk submitted_by (user yang submit, biasanya guru)
            $table->foreign('submitted_by', 'fk_pertemuan_submitted_by')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pertemuan', function (Blueprint $table) {
            // Drop foreign key dulu
            $table->dropForeign('fk_pertemuan_submitted_by');
            
            // Drop kolom
            $table->dropColumn([
                'tanggal_absen_dibuka',
                'tanggal_absen_ditutup',
                'jam_absen_buka',
                'jam_absen_tutup',
                'waktu_absen_dibuka',
                'waktu_absen_ditutup',
                'is_submitted',
                'submitted_at',
                'submitted_by'
            ]);
        });
    }
};
