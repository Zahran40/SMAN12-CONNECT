<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pertemuan', function (Blueprint $table) {
            $table->bigInteger('id_pertemuan', true);
            $table->bigInteger('jadwal_id')->index('fk_pertemuan_jadwal');
            $table->unsignedInteger('nomor_pertemuan');
            $table->date('tanggal_pertemuan')->nullable();
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->text('topik_bahasan')->nullable();
            $table->enum('status_sesi', ['Buka', 'Ditutup'])->default('Buka');
            $table->date('tanggal_absen_dibuka')->nullable();
            $table->date('tanggal_absen_ditutup')->nullable();
            $table->time('jam_absen_buka')->nullable();
            $table->time('jam_absen_tutup')->nullable();
            $table->dateTime('waktu_absen_dibuka')->nullable();
            $table->dateTime('waktu_absen_ditutup')->nullable();
            $table->boolean('is_submitted')->default(false);
            $table->dateTime('submitted_at')->nullable();
            $table->bigInteger('submitted_by')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->unique(['jadwal_id', 'nomor_pertemuan'], 'uk_pertemuan_unik');
            $table->foreign(['jadwal_id'], 'fk_pertemuan_jadwal')->references(['id_jadwal'])->on('jadwal_pelajaran')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('submitted_by', 'fk_pertemuan_submitted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pertemuan');
    }
};