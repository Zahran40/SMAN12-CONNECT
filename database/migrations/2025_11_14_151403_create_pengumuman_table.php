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
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->bigInteger('id_pengumuman', true);
            $table->string('judul', 250);
            $table->text('isi_pengumuman');
            $table->date('tgl_publikasi')->index('idx_tanggal');
            $table->bigInteger('author_id')->index('idx_author');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
