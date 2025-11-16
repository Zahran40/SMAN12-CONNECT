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
        Schema::table('pertemuan', function (Blueprint $table) {
            // Make all date/time columns nullable
            $table->date('tanggal_pertemuan')->nullable()->change();
            $table->date('tanggal_absen_dibuka')->nullable()->change();
            $table->date('tanggal_absen_ditutup')->nullable()->change();
            $table->time('jam_absen_buka')->nullable()->change();
            $table->time('jam_absen_tutup')->nullable()->change();
            $table->text('topik_bahasan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse
    }
};
