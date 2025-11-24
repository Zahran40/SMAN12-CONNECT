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
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->bigInteger('batch_id')->nullable()->after('id_pembayaran')->index('idx_batch');
            
            // Foreign Key
            $table->foreign(['batch_id'], 'fk_pembayaran_batch')->references(['id_batch'])->on('tagihan_batch')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->dropForeign('fk_pembayaran_batch');
            $table->dropIndex('idx_batch');
            $table->dropColumn('batch_id');
        });
    }
};
