<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk menyimpan catatan perilaku siswa (kedisiplinan, prestasi, pelanggaran)
     * Digunakan oleh Guru, Wali Kelas, BK, dan dapat dilihat Orang Tua
     */
    public function up(): void
    {
        Schema::create('laporan_perilaku', function (Blueprint $table) {
            $table->bigInteger('id_laporan', true);
            $table->bigInteger('siswa_id')->index('idx_siswa');
            $table->bigInteger('pelapor_id')->index('idx_pelapor')->comment('User ID guru/wali kelas yang membuat laporan');
            $table->bigInteger('tahun_ajaran_id')->index('idx_tahun');
            $table->enum('jenis', ['Prestasi', 'Pelanggaran', 'Catatan Positif', 'Catatan Negatif'])->index('idx_jenis');
            $table->string('judul', 200);
            $table->text('deskripsi');
            $table->date('tanggal_kejadian');
            $table->string('lokasi', 150)->nullable();
            $table->enum('tingkat_severity', ['Ringan', 'Sedang', 'Berat'])->default('Ringan')->nullable();
            $table->integer('poin')->default(0)->comment('Poin positif/negatif untuk sistem reward/punishment');
            $table->string('bukti_foto', 255)->nullable()->comment('Path foto bukti jika ada');
            $table->text('tindak_lanjut')->nullable()->comment('Tindakan yang sudah dilakukan');
            $table->enum('status', ['Baru', 'Ditindaklanjuti', 'Selesai'])->default('Baru')->index('idx_status');
            $table->boolean('notifikasi_ortu')->default(true)->comment('Apakah ortu sudah dinotifikasi');
            $table->timestamp('dibaca_ortu_at')->nullable()->comment('Kapan ortu membaca laporan ini');
            $table->timestamps();

            // Foreign keys
            $table->foreign(['siswa_id'], 'fk_laporan_siswa')
                ->references(['id_siswa'])->on('siswa')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['pelapor_id'], 'fk_laporan_pelapor')
                ->references(['id'])->on('users')
                ->onUpdate('cascade')->onDelete('restrict');
            
            $table->foreign(['tahun_ajaran_id'], 'fk_laporan_tahun')
                ->references(['id_tahun_ajaran'])->on('tahun_ajaran')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_perilaku');
    }
};
