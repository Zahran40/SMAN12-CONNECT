<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk goal tracking dan KPI sekolah
     */
    public function up(): void
    {
        Schema::create('target_sekolah', function (Blueprint $table) {
            $table->bigInteger('id_target', true);
            $table->bigInteger('tahun_ajaran_id')->index('idx_tahun');
            $table->string('nama_target', 200);
            $table->text('deskripsi')->nullable();
            $table->enum('kategori', ['Akademik', 'Keuangan', 'Kehadiran', 'Prestasi', 'Lainnya'])->index('idx_kategori');
            $table->string('indikator', 150)->comment('Misalnya: Rata-rata Nilai, Persentase Kehadiran, dll');
            $table->decimal('target_nilai', 10, 2)->comment('Nilai target yang ingin dicapai');
            $table->decimal('nilai_saat_ini', 10, 2)->default(0)->comment('Progress saat ini');
            $table->string('satuan', 50)->default('%')->comment('%, Rupiah, Siswa, dll');
            $table->date('tanggal_mulai');
            $table->date('tanggal_target');
            $table->enum('status', ['Belum Mulai', 'Sedang Berjalan', 'Tercapai', 'Tidak Tercapai', 'Dibatalkan'])
                ->default('Belum Mulai')->index('idx_status');
            $table->integer('persentase_pencapaian')->default(0)->comment('Dihitung otomatis dari nilai_saat_ini/target_nilai');
            $table->bigInteger('dibuat_oleh')->comment('User ID Kepala Sekolah');
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign(['tahun_ajaran_id'], 'fk_target_tahun')
                ->references(['id_tahun_ajaran'])->on('tahun_ajaran')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['dibuat_oleh'], 'fk_target_pembuat')
                ->references(['id'])->on('users')
                ->onUpdate('cascade')->onDelete('restrict');
        });

        // Log update progress target
        Schema::create('log_progress_target', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('target_id')->index('idx_target');
            $table->decimal('nilai_sebelum', 10, 2);
            $table->decimal('nilai_sesudah', 10, 2);
            $table->text('catatan')->nullable();
            $table->bigInteger('diupdate_oleh')->comment('User ID');
            $table->timestamps();

            // Foreign keys
            $table->foreign(['target_id'], 'fk_log_target')
                ->references(['id_target'])->on('target_sekolah')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['diupdate_oleh'], 'fk_log_updater')
                ->references(['id'])->on('users')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_progress_target');
        Schema::dropIfExists('target_sekolah');
    }
};
