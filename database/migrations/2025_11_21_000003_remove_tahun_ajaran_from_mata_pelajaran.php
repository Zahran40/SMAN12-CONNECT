<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Menghapus tahun_ajaran_id dari mata_pelajaran
     * Karena mata pelajaran adalah MASTER DATA yang tidak berubah per tahun ajaran
     * Relasi tahun ajaran ke mapel sudah ada di jadwal_pelajaran
     */
    public function up(): void
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            // Drop foreign key dan kolom tahun_ajaran_id
            if (Schema::hasColumn('mata_pelajaran', 'tahun_ajaran_id')) {
                $table->dropForeign(['tahun_ajaran_id']);
                $table->dropColumn('tahun_ajaran_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->bigInteger('tahun_ajaran_id')->nullable()->after('kategori');
            $table->foreign('tahun_ajaran_id')->references('id_tahun_ajaran')->on('tahun_ajaran')->onDelete('set null');
        });
    }
};
