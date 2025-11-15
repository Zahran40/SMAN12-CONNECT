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
        Schema::table('tugas', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('tugas', 'deskripsi_tugas')) {
                $table->text('deskripsi_tugas')->nullable()->after('judul_tugas');
            }
            if (!Schema::hasColumn('tugas', 'jam_buka')) {
                $table->time('jam_buka')->nullable();
            }
            if (!Schema::hasColumn('tugas', 'jam_tutup')) {
                $table->time('jam_tutup')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->renameColumn('file_tugas', 'file_path');
            $table->dropColumn(['deskripsi_tugas', 'tgl_upload', 'jam_buka', 'jam_tutup']);
        });
    }
};
