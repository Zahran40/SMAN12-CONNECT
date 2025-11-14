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
        Schema::create('pertemuan', function (Blueprint $table) {
            $table->bigInteger('id_pertemuan', true);
            $table->bigInteger('jadwal_id')->index('fk_pertemuan_jadwal');
            $table->date('tanggal_pertemuan');
            $table->string('topik_bahasan', 250)->nullable();
            $table->enum('status_sesi', ['Buka', 'Ditutup'])->default('Buka');
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->unique(['jadwal_id', 'tanggal_pertemuan'], 'uk_pertemuan_unik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertemuan');
    }
};
