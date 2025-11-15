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
        Schema::table('pengumuman', function (Blueprint $table) {
            // Add file_lampiran if not exists
            if (!Schema::hasColumn('pengumuman', 'file_lampiran')) {
                $table->string('file_lampiran', 255)->nullable()->after('isi_pengumuman');
            }
            
            // Add status if not exists
            if (!Schema::hasColumn('pengumuman', 'status')) {
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('file_lampiran');
            }
            
            // Add tanggal_dibuat as alias or use tgl_publikasi
            if (!Schema::hasColumn('pengumuman', 'tanggal_dibuat')) {
                $table->timestamp('tanggal_dibuat')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            if (Schema::hasColumn('pengumuman', 'file_lampiran')) {
                $table->dropColumn('file_lampiran');
            }
            if (Schema::hasColumn('pengumuman', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('pengumuman', 'tanggal_dibuat')) {
                $table->dropColumn('tanggal_dibuat');
            }
        });
    }
};
