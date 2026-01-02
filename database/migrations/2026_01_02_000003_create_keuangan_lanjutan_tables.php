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
        // Kategori Keuangan (COA Sederhana)
        Schema::create('kategori_keuangan', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('nama_kategori', 100); // Cth: Gaji Guru, Listrik, Dana BOS
            $table->enum('jenis', ['Pemasukan', 'Pengeluaran']);
        });

        // Transaksi Keuangan (Pemasukan Non-SPP & Pengeluaran)
        Schema::create('transaksi_sekolah', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('kategori_id');
            $table->unsignedBigInteger('user_id'); // Siapa yang input (Bendahara)
            $table->string('judul_transaksi', 200);
            $table->decimal('jumlah', 15, 2);
            $table->date('tgl_transaksi');
            $table->enum('tipe', ['Pemasukan', 'Pengeluaran']);
            $table->enum('metode', ['Tunai', 'Transfer'])->default('Tunai');
            $table->string('bukti_transaksi', 255)->nullable(); // Foto struk/nota
            $table->text('keterangan')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            // Index
            $table->index('tgl_transaksi', 'idx_transaksi_tgl');
            
            // Foreign Key
            $table->foreign('kategori_id', 'fk_trans_kategori')
                  ->references('id_kategori')
                  ->on('kategori_keuangan')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_sekolah');
        Schema::dropIfExists('kategori_keuangan');
    }
};
