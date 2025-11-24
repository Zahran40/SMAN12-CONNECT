<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->bigInteger('id_tahun_ajaran', true);
            $table->year('tahun_mulai');
            $table->year('tahun_selesai');
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Tidak Aktif');
            $table->unique(['tahun_mulai', 'tahun_selesai', 'semester'], 'uk_tahun_semester');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tahun_ajaran');
    }
};