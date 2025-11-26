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
        // Constraint sudah tidak ada di database, skip migration
        // Hanya kode_mapel yang unique, nama_mapel sudah bisa duplikat
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            // Add back unique constraint
            $table->unique('nama_mapel');
        });
    }
};
