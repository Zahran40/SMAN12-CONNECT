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
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->bigInteger('id_tahun_ajaran', true);
            $table->year('tahun');
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->enum('status', ['Aktif', 'Tidak Aktif']);

            $table->unique(['tahun', 'semester'], 'uk_tahun_semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_ajaran');
    }
};
