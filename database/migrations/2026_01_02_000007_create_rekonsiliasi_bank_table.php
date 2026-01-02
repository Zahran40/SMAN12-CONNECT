<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk rekonsiliasi pembayaran dengan bank statement
     */
    public function up(): void
    {
        Schema::create('rekonsiliasi_bank', function (Blueprint $table) {
            $table->bigInteger('id_rekonsiliasi', true);
            $table->date('tanggal_rekonsiliasi')->index('idx_tanggal');
            $table->string('nama_bank', 100);
            $table->string('nomor_rekening', 50);
            $table->decimal('saldo_awal', 15, 2);
            $table->decimal('total_pemasukan', 15, 2)->default(0);
            $table->decimal('total_pengeluaran', 15, 2)->default(0);
            $table->decimal('saldo_akhir', 15, 2);
            $table->decimal('saldo_sistem', 15, 2)->comment('Saldo menurut sistem');
            $table->decimal('selisih', 15, 2)->default(0)->comment('Selisih antara bank dan sistem');
            $table->enum('status', ['Match', 'Selisih', 'Pending'])->default('Pending')->index('idx_status');
            $table->text('catatan')->nullable();
            $table->string('file_statement', 255)->nullable()->comment('Path file bank statement PDF/Excel');
            $table->bigInteger('dibuat_oleh')->comment('User ID bendahara');
            $table->timestamps();

            // Foreign key
            $table->foreign(['dibuat_oleh'], 'fk_rekonsiliasi_pembuat')
                ->references(['id'])->on('users')
                ->onUpdate('cascade')->onDelete('restrict');
        });

        // Detail item rekonsiliasi
        Schema::create('detail_rekonsiliasi', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('rekonsiliasi_id')->index('idx_rekonsiliasi');
            $table->bigInteger('pembayaran_id')->nullable()->index('idx_pembayaran')->comment('Link ke pembayaran_spp jika ada');
            $table->date('tanggal_transaksi');
            $table->string('deskripsi', 200);
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('kredit', 15, 2)->default(0);
            $table->string('referensi', 100)->nullable()->comment('No referensi dari bank');
            $table->enum('status_match', ['Matched', 'Unmatched', 'Manual'])->default('Unmatched')->index('idx_match');
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign(['rekonsiliasi_id'], 'fk_detail_rekonsiliasi')
                ->references(['id_rekonsiliasi'])->on('rekonsiliasi_bank')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['pembayaran_id'], 'fk_detail_pembayaran')
                ->references(['id_pembayaran'])->on('pembayaran_spp')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_rekonsiliasi');
        Schema::dropIfExists('rekonsiliasi_bank');
    }
};
