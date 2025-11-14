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
        Schema::create('siswa', function (Blueprint $table) {
            $table->bigInteger('id_siswa', true);
            $table->bigInteger('user_id')->nullable()->index('idx_user');
            $table->string('nis', 20)->nullable()->unique('uk_nis');
            $table->string('nama_lengkap', 250);
            $table->date('tgl_lahir');
            $table->string('alamat', 250);
            $table->bigInteger('kelas_id')->index('idx_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
