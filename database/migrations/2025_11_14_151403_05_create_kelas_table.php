<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->bigInteger('id_kelas', true);
            $table->string('nama_kelas', 250);
            $table->enum('tingkat', ['10', '11', '12']);
            $table->string('jurusan', 250);
            $table->bigInteger('tahun_ajaran_id')->nullable()->index('idx_tahun_ajaran');
            $table->bigInteger('wali_kelas_id')->nullable()->index('idx_wali');
            $table->foreign(['wali_kelas_id'], 'fk_kelas_wali')->references(['id_guru'])->on('guru')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['tahun_ajaran_id'], 'fk_kelas_tahun')->references(['id_tahun_ajaran'])->on('tahun_ajaran')->onUpdate('cascade')->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};