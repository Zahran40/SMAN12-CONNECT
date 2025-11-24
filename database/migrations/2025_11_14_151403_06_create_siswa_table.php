<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->bigInteger('id_siswa', true);
            $table->bigInteger('user_id')->nullable()->index('idx_user');
            $table->string('nis', 20)->nullable()->unique('uk_nis');
            $table->string('nisn', 20)->nullable()->unique('uk_nisn');
            $table->string('nama_lengkap', 250);
            $table->date('tgl_lahir');
            $table->string('tempat_lahir', 100)->nullable();
            $table->string('alamat', 250)->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('agama', 50)->nullable();
            $table->string('golongan_darah', 5)->nullable();
            $table->string('foto_profil', 255)->nullable();
            $table->bigInteger('kelas_id')->nullable()->index('idx_kelas'); 
            $table->foreign(['user_id'], 'fk_siswa_user')->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['kelas_id'], 'fk_siswa_kelas')->references(['id_kelas'])->on('kelas')->onUpdate('cascade')->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};