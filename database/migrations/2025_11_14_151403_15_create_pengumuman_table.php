<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->bigInteger('id_pengumuman', true);
            $table->string('judul', 250);
            $table->text('isi_pengumuman');
            $table->string('file_lampiran', 255)->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamp('tanggal_dibuat')->nullable();
            $table->date('tgl_publikasi')->index('idx_tanggal');
            $table->string('hari', 20)->nullable();
            $table->enum('target_role', ['admin', 'guru', 'siswa', 'Semua'])->default('Semua');
            $table->bigInteger('author_id')->index('idx_author');
            $table->foreign(['author_id'], 'fk_pengumuman_author')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};