<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk evaluasi kinerja guru oleh Kepala Sekolah
     */
    public function up(): void
    {
        Schema::create('evaluasi_kinerja_guru', function (Blueprint $table) {
            $table->bigInteger('id_evaluasi', true);
            $table->bigInteger('guru_id')->index('idx_guru');
            $table->bigInteger('tahun_ajaran_id')->index('idx_tahun');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->bigInteger('evaluator_id')->comment('User ID Kepala Sekolah');
            
            // Aspek penilaian (skala 1-100)
            $table->integer('ketepatan_waktu_mengajar')->default(0)->comment('Berdasarkan presensi pertemuan');
            $table->integer('kelengkapan_materi')->default(0)->comment('Upload materi & tugas');
            $table->integer('interaksi_siswa')->default(0)->comment('Response time, engagement');
            $table->integer('kualitas_pembelajaran')->default(0)->comment('Rata-rata nilai siswa');
            $table->integer('administrasi_kelas')->default(0)->comment('Kelengkapan input nilai, absensi');
            $table->integer('pengembangan_diri')->default(0)->comment('Pelatihan, sertifikasi');
            
            $table->decimal('skor_total', 5, 2)->default(0)->comment('Average dari semua aspek');
            $table->enum('kategori', ['Sangat Baik', 'Baik', 'Cukup', 'Kurang'])->nullable()->index('idx_kategori');
            
            $table->text('catatan_positif')->nullable();
            $table->text('area_perbaikan')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->enum('status', ['Draft', 'Final', 'Direvisi'])->default('Draft')->index('idx_status');
            $table->timestamp('tanggal_evaluasi')->useCurrent();
            $table->timestamps();

            // Foreign keys
            $table->foreign(['guru_id'], 'fk_evaluasi_guru')
                ->references(['id_guru'])->on('guru')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['tahun_ajaran_id'], 'fk_evaluasi_tahun')
                ->references(['id_tahun_ajaran'])->on('tahun_ajaran')
                ->onUpdate('cascade')->onDelete('restrict');
            
            $table->foreign(['evaluator_id'], 'fk_evaluasi_evaluator')
                ->references(['id'])->on('users')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_kinerja_guru');
    }
};
