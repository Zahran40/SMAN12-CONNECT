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
        Schema::table('guru', function (Blueprint $table) {
            $table->bigInteger('mapel_id')->nullable()->after('golongan_darah')->index('idx_mapel');
            $table->foreign(['mapel_id'], 'fk_guru_mapel')->references(['id_mapel'])->on('mata_pelajaran')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->dropForeign('fk_guru_mapel');
            $table->dropColumn('mapel_id');
        });
    }
};
