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
        Schema::create('tagihan_batch', function (Blueprint $table) {
            $table->bigInteger('id_batch', true);
            $table->bigInteger('admin_id')->index('idx_admin');
            $table->bigInteger('tahun_ajaran_id')->index('idx_tahun_ajaran');
            $table->integer('bulan');
            $table->year('tahun');
            $table->integer('jumlah_siswa')->comment('Jumlah siswa dalam batch ini');
            $table->decimal('total_nominal', 15)->comment('Total nominal tagihan yang dibuat');
            $table->text('deskripsi')->nullable()->comment('Keterangan batch, mis: "SPP Januari 2025 - Kelas X"');
            $table->timestamp('created_at')->nullable()->useCurrent();

            // Foreign Keys
            $table->foreign(['admin_id'], 'fk_batch_admin')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['tahun_ajaran_id'], 'fk_batch_tahun')->references(['id_tahun_ajaran'])->on('tahun_ajaran')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_batch');
    }
};
