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
            // Hapus file_tugas (gunakan file_path saja)
            if (Schema::hasColumn('tugas', 'file_tugas')) {
                $table->dropColumn('file_tugas');
            }
            
            // Hapus tanggal_deadline (gunakan deadline saja)
            if (Schema::hasColumn('tugas', 'tanggal_deadline')) {
                $table->dropColumn('tanggal_deadline');
            }
            
            // Hapus tgl_upload (gunakan created_at saja)
            if (Schema::hasColumn('tugas', 'tgl_upload')) {
                $table->dropColumn('tgl_upload');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->string('file_tugas')->nullable();
            $table->date('tanggal_deadline')->nullable();
            $table->timestamp('tgl_upload')->nullable();
        });
    }
};
