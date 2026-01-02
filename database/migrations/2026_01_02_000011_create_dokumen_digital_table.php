<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk dokumen digital dengan digital signature
     */
    public function up(): void
    {
        Schema::create('dokumen_digital', function (Blueprint $table) {
            $table->bigInteger('id_dokumen', true);
            $table->string('nomor_dokumen', 100)->unique('uk_nomor_dokumen')->index('idx_nomor');
            $table->string('judul', 200);
            $table->enum('jenis_dokumen', [
                'SK', 
                'Surat Tugas', 
                'Laporan', 
                'Surat Keterangan', 
                'MOU', 
                'Kontrak',
                'Lainnya'
            ])->index('idx_jenis');
            $table->text('deskripsi')->nullable();
            $table->string('file_path', 255)->comment('Path PDF dokumen');
            $table->bigInteger('dibuat_oleh')->comment('User ID pembuat dokumen');
            
            // Digital signature workflow
            $table->enum('status', ['Draft', 'Menunggu Tanda Tangan', 'Ditandatangani', 'Ditolak', 'Dibatalkan'])
                ->default('Draft')->index('idx_status');
            $table->bigInteger('menunggu_ttd_dari')->nullable()->comment('User ID yang harus tanda tangan');
            $table->bigInteger('ditandatangani_oleh')->nullable()->comment('User ID yang sudah tanda tangan');
            $table->timestamp('tanggal_ttd')->nullable();
            $table->string('signature_path', 255)->nullable()->comment('Path gambar tanda tangan');
            $table->string('signature_hash', 255)->nullable()->comment('Hash untuk verifikasi integritas');
            $table->text('catatan_penolakan')->nullable();
            
            $table->date('tanggal_dokumen');
            $table->date('tanggal_berlaku')->nullable();
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->text('tags')->nullable()->comment('JSON array untuk tags/kategori');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign(['dibuat_oleh'], 'fk_dokumen_pembuat')
                ->references(['id'])->on('users')
                ->onUpdate('cascade')->onDelete('restrict');
            
            $table->foreign(['menunggu_ttd_dari'], 'fk_dokumen_penandatangan')
                ->references(['id'])->on('users')
                ->onUpdate('cascade')->onDelete('set null');
            
            $table->foreign(['ditandatangani_oleh'], 'fk_dokumen_ttd')
                ->references(['id'])->on('users')
                ->onUpdate('cascade')->onDelete('set null');
        });

        // Log history dokumen
        Schema::create('log_dokumen', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('dokumen_id')->index('idx_dokumen');
            $table->string('aksi', 50)->comment('Create, Update, Sign, Reject, Delete');
            $table->text('deskripsi')->nullable();
            $table->bigInteger('oleh_user_id')->comment('User ID yang melakukan aksi');
            $table->timestamps();

            // Foreign keys
            $table->foreign(['dokumen_id'], 'fk_log_dokumen')
                ->references(['id_dokumen'])->on('dokumen_digital')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['oleh_user_id'], 'fk_log_user')
                ->references(['id'])->on('users')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_dokumen');
        Schema::dropIfExists('dokumen_digital');
    }
};
