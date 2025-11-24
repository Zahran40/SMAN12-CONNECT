<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->bigInteger('id_mapel', true);
            $table->string('kode_mapel', 20)->unique('uk_kode');
            $table->string('nama_mapel', 250);
            $table->enum('kategori', ['Umum', 'Kelas X', 'MIPA', 'IPS', 'Bahasa', 'Mulok'])->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('mata_pelajaran');
    }
};