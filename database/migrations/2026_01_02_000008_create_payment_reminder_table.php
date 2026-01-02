<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk log reminder pembayaran (WhatsApp/Email)
     */
    public function up(): void
    {
        Schema::create('payment_reminder', function (Blueprint $table) {
            $table->bigInteger('id_reminder', true);
            $table->bigInteger('pembayaran_id')->index('idx_pembayaran');
            $table->bigInteger('siswa_id')->index('idx_siswa');
            $table->enum('metode', ['WhatsApp', 'Email', 'SMS', 'Notifikasi App'])->index('idx_metode');
            $table->string('tujuan', 150)->comment('Nomor WA/Email/Phone');
            $table->text('pesan')->nullable();
            $table->enum('status', ['Pending', 'Terkirim', 'Gagal', 'Dibaca'])->default('Pending')->index('idx_status');
            $table->timestamp('waktu_kirim')->nullable();
            $table->timestamp('waktu_dibaca')->nullable();
            $table->integer('percobaan_ke')->default(1);
            $table->text('error_message')->nullable();
            $table->string('external_id', 100)->nullable()->comment('ID dari service external (WhatsApp API, Email Service)');
            $table->boolean('is_auto')->default(true)->comment('Auto reminder atau manual');
            $table->bigInteger('dikirim_oleh')->nullable()->comment('User ID bendahara jika manual');
            $table->timestamps();

            // Foreign keys
            $table->foreign(['pembayaran_id'], 'fk_reminder_pembayaran')
                ->references(['id_pembayaran'])->on('pembayaran_spp')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['siswa_id'], 'fk_reminder_siswa')
                ->references(['id_siswa'])->on('siswa')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['dikirim_oleh'], 'fk_reminder_pengirim')
                ->references(['id'])->on('users')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_reminder');
    }
};
