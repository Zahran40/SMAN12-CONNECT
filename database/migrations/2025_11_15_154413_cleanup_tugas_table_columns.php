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
            // Drop duplicate deskripsi column (keep deskripsi_tugas)
            if (Schema::hasColumn('tugas', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
            
            // Change deadline type from datetime to date
            $table->date('deadline')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->text('deskripsi')->nullable();
            $table->datetime('deadline')->nullable()->change();
        });
    }
};
