<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk manajemen diskon dan beasiswa SPP
     */
    public function up(): void
    {
        Schema::create('diskon_beasiswa', function (Blueprint $table) {
            $table->bigInteger('id_diskon', true);
            $table->string('nama_program', 150)->index('idx_nama');
            $table->text('deskripsi')->nullable();
            $table->enum('jenis', ['Diskon', 'Beasiswa', 'Keringanan'])->index('idx_jenis');
            $table->enum('tipe_potongan', ['Persentase', 'Nominal'])->default('Persentase');
            $table->decimal('nilai_potongan', 15, 2)->comment('Nilai dalam % atau Rupiah');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif')->index('idx_status');
            $table->string('syarat', 255)->nullable()->comment('Syarat mendapatkan diskon/beasiswa');
            $table->string('dokumen_persyaratan', 255)->nullable()->comment('Path dokumen persyaratan');
            $table->bigInteger('dibuat_oleh')->nullable()->comment('User ID bendahara');
            $table->timestamps();

            // Foreign key
            $table->foreign(['dibuat_oleh'], 'fk_diskon_pembuat')
                ->references(['id'])->on('users')
                ->onUpdate('cascade')->onDelete('set null');
        });

        // Tabel many-to-many untuk siswa yang mendapat diskon/beasiswa
        Schema::create('siswa_diskon', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('siswa_id')->index('idx_siswa');
            $table->bigInteger('diskon_id')->index('idx_diskon');
            $table->bigInteger('tahun_ajaran_id')->index('idx_tahun');
            $table->date('tanggal_diberikan');
            $table->text('catatan')->nullable();
            $table->string('dokumen_pendukung', 255)->nullable();
            $table->enum('status_verifikasi', ['Pending', 'Disetujui', 'Ditolak'])->default('Pending')->index('idx_status');
            $table->bigInteger('diverifikasi_oleh')->nullable()->comment('User ID kepsek/bendahara');
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();

            // Unique constraint: satu siswa tidak boleh dapat diskon yang sama 2x di tahun ajaran yang sama
            $table->unique(['siswa_id', 'diskon_id', 'tahun_ajaran_id'], 'uk_siswa_diskon');

            // Foreign keys
            $table->foreign(['siswa_id'], 'fk_siswa_diskon_siswa')
                ->references(['id_siswa'])->on('siswa')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['diskon_id'], 'fk_siswa_diskon_diskon')
                ->references(['id_diskon'])->on('diskon_beasiswa')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['tahun_ajaran_id'], 'fk_siswa_diskon_tahun')
                ->references(['id_tahun_ajaran'])->on('tahun_ajaran')
                ->onUpdate('cascade')->onDelete('restrict');
            
            $table->foreign(['diverifikasi_oleh'], 'fk_siswa_diskon_verifikator')
                ->references(['id'])->on('users')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_diskon');
        Schema::dropIfExists('diskon_beasiswa');
    }
};
