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
            $table->bigInteger('siswa_id');
            $table->bigInteger('tahun_ajaran_id')->index('idx_tahun');
            $table->integer('bulan');
            $table->string('nama_tagihan', 250)->nullable();
            $table->decimal('jumlah_bayar', 15);
            $table->date('tgl_bayar')->nullable();
            $table->enum('status', ['Lunas', 'Belum Lunas'])->default('Belum Lunas')->index('idx_status');
            $table->enum('metode_pembayaran', ['Tunai', 'Transfer', 'E-Wallet', 'Kartu'])->nullable()->default('Tunai');
            $table->string('nomor_va', 40)->nullable()->index('idx_nomor_va');
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->unique(['siswa_id', 'tahun_ajaran_id', 'bulan'], 'uk_pembayaran');
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
