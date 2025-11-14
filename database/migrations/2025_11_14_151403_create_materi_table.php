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
        Schema::create('materi', function (Blueprint $table) {
            $table->bigInteger('id_materi', true);
            $table->bigInteger('jadwal_id')->index('fk_materi_jadwal');
            $table->string('judul_materi', 250);
            $table->text('deskripsi')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamp('tgl_upload')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi');
    }
};
