<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->bigInteger('id_log', true);
            $table->string('jenis_aktivitas', 100)->nullable()->index('idx_jenis'); 
            $table->text('deskripsi')->nullable(); 
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->string('role', 20)->nullable(); 
            $table->string('nama_tabel', 100)->nullable()->index('idx_tabel'); 
            $table->bigInteger('id_referensi')->nullable(); 
            $table->string('aksi', 20)->nullable(); 
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('waktu')->nullable()->useCurrent()->index('idx_waktu');
            $table->index('user_id');
            $table->index('role');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};