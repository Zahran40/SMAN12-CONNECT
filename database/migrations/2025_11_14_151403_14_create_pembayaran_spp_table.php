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
        Schema::create('pembayaran_spp', function (Blueprint $table) {
            $table->bigInteger('id_pembayaran', true);
            $table->bigInteger('siswa_id')->index('idx_siswa');
            $table->bigInteger('tahun_ajaran_id')->index('idx_tahun');
            $table->string('nama_tagihan', 250)->nullable();
            $table->integer('bulan');
            $table->year('tahun');
            $table->decimal('jumlah_bayar', 15);
            $table->date('tgl_bayar')->nullable();
            $table->enum('status', ['Lunas', 'Belum Lunas'])->default('Belum Lunas')->index('idx_status');
            $table->enum('metode_pembayaran', ['Tunai', 'Transfer', 'Kartu', 'E-Wallet'])->nullable()->default('Tunai');
            $table->string('nomor_va', 40)->nullable()->index('idx_nomor_va');
            
            // Midtrans Integration Columns
            $table->string('midtrans_order_id', 100)->nullable();
            $table->string('midtrans_transaction_id', 100)->nullable();
            $table->string('midtrans_payment_type', 50)->nullable();
            $table->enum('midtrans_transaction_status', ['pending', 'settlement', 'expire', 'cancel', 'deny'])->nullable();
            $table->text('midtrans_response')->nullable();
            $table->timestamp('midtrans_paid_at')->nullable();
            
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->unique(['siswa_id', 'tahun_ajaran_id', 'bulan'], 'uk_pembayaran');
            
            // Foreign Keys
            $table->foreign(['siswa_id'], 'fk_pembayaran_siswa')->references(['id_siswa'])->on('siswa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['tahun_ajaran_id'], 'fk_pembayaran_tahun')->references(['id_tahun_ajaran'])->on('tahun_ajaran')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_spp');
    }
};
