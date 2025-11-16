<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            // Drop column semester lama
            $table->dropColumn('semester');
        });
        
        Schema::table('nilai', function (Blueprint $table) {
            // Tambah column semester baru dengan enum Ganjil/Genap
            $table->enum('semester', ['Ganjil', 'Genap'])->default('Ganjil')->after('tahun_ajaran_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropColumn('semester');
        });
        
        Schema::table('nilai', function (Blueprint $table) {
            $table->enum('semester', ['1', '2'])->default('1')->after('tahun_ajaran_id');
        });
    }
};
