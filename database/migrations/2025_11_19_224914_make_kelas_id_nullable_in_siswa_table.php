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
        Schema::table('siswa', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign('fk_siswa_kelas');
            
            // Make kelas_id nullable
            $table->bigInteger('kelas_id')->nullable()->change();
            
            // Re-add foreign key with nullable constraint
            $table->foreign('kelas_id', 'fk_siswa_kelas')
                  ->references('id_kelas')
                  ->on('kelas')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign('fk_siswa_kelas');
            
            // Make kelas_id not nullable
            $table->bigInteger('kelas_id')->nullable(false)->change();
            
            // Re-add foreign key
            $table->foreign('kelas_id', 'fk_siswa_kelas')
                  ->references('id_kelas')
                  ->on('kelas')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }
};
