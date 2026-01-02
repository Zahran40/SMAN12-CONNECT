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
        // Tabel Profil Orang Tua
        Schema::create('orang_tua', function (Blueprint $table) {
            $table->id('id_orang_tua');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nik', 16);
            $table->string('nama_lengkap', 250);
            $table->string('no_telepon', 20);
            $table->string('pekerjaan', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto_profil', 255)->nullable();
            
            // Foreign Key
            $table->foreign('user_id', 'fk_ortu_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        // Tabel Relasi Orang Tua - Siswa
        Schema::create('relasi_ortu_siswa', function (Blueprint $table) {
            $table->id('id_relasi');
            $table->unsignedBigInteger('orang_tua_id');
            $table->unsignedBigInteger('siswa_id');
            $table->enum('hubungan', ['Ayah', 'Ibu', 'Wali'])->default('Wali');
            $table->boolean('is_active')->default(true);
            
            // Unique Constraint
            $table->unique(['orang_tua_id', 'siswa_id'], 'uk_relasi');
            
            // Foreign Keys
            $table->foreign('orang_tua_id', 'fk_relasi_ortu')
                  ->references('id_orang_tua')
                  ->on('orang_tua')
                  ->onDelete('cascade');
                  
            $table->foreign('siswa_id', 'fk_relasi_siswa')
                  ->references('id_siswa')
                  ->on('siswa')
                  ->onDelete('cascade');
            
            // Indexes
            $table->index('siswa_id', 'idx_relasi_siswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relasi_ortu_siswa');
        Schema::dropIfExists('orang_tua');
    }
};
