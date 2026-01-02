<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk manajemen refund/pengembalian pembayaran
     */
    public function up(): void
    {
        Schema::create('refund_pembayaran', function (Blueprint $table) {
            $table->bigInteger('id_refund', true);
            $table->bigInteger('pembayaran_id')->index('idx_pembayaran');
            $table->bigInteger('siswa_id')->index('idx_siswa');
            $table->decimal('jumlah_refund', 15, 2);
            $table->text('alasan');
            $table->enum('jenis', ['Pembatalan', 'Kelebihan Bayar', 'Kesalahan Admin', 'Lainnya'])->index('idx_jenis');
            $table->enum('metode_refund', ['Transfer Bank', 'Tunai', 'Potong Tagihan Berikutnya'])->default('Transfer Bank');
            $table->string('nomor_rekening', 50)->nullable();
            $table->string('nama_bank', 100)->nullable();
            $table->string('nama_pemilik_rekening', 150)->nullable();
            $table->enum('status', ['Pending', 'Disetujui', 'Ditolak', 'Diproses', 'Selesai'])->default('Pending')->index('idx_status');
            $table->bigInteger('diajukan_oleh')->comment('User ID yang mengajukan');
            $table->bigInteger('disetujui_oleh')->nullable()->comment('User ID kepsek/bendahara');
            $table->timestamp('tanggal_pengajuan')->useCurrent();
            $table->timestamp('tanggal_persetujuan')->nullable();
            $table->timestamp('tanggal_refund')->nullable();
            $table->text('catatan_bendahara')->nullable();
            $table->string('bukti_transfer', 255)->nullable()->comment('Path bukti transfer refund');
            $table->timestamps();

            // Foreign keys
            $table->foreign(['pembayaran_id'], 'fk_refund_pembayaran')
                ->references(['id_pembayaran'])->on('pembayaran_spp')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['siswa_id'], 'fk_refund_siswa')
                ->references(['id_siswa'])->on('siswa')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['diajukan_oleh'], 'fk_refund_pengaju')
                ->references(['id'])->on('users')
                ->onUpdate('cascade')->onDelete('restrict');
            
            $table->foreign(['disetujui_oleh'], 'fk_refund_penyetuju')
                ->references(['id'])->on('users')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_pembayaran');
    }
};
