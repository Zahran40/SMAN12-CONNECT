<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru', function (Blueprint $table) {
            $table->bigInteger('id_guru', true);
            $table->bigInteger('user_id')->nullable()->index('idx_user');
            $table->string('nip', 20)->unique('uk_nip');
            $table->string('nama_lengkap', 250);
            $table->date('tgl_lahir')->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->string('alamat', 300)->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('agama', 50)->nullable();
            $table->string('golongan_darah', 5)->nullable();
            $table->bigInteger('mapel_id')->nullable()->index('idx_mapel');
            $table->string('foto_profil', 255)->nullable();
            $table->foreign(['user_id'], 'fk_guru_user')->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['mapel_id'], 'fk_guru_mapel')->references(['id_mapel'])->on('mata_pelajaran')->onUpdate('no action')->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};