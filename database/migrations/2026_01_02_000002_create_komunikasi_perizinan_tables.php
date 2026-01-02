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
        // Tabel Perizinan Online (Sakit/Izin/Cuti)
        Schema::create('perizinan', function (Blueprint $table) {
            $table->id('id_izin');
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('orang_tua_id'); // Yang mengajukan
            $table->unsignedBigInteger('tahun_ajaran_id');
            $table->enum('jenis_izin', ['Sakit', 'Izin', 'Lainnya']);
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->text('keterangan');
            $table->string('file_bukti', 255)->nullable(); // Foto surat dokter/KTP
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->unsignedBigInteger('approval_by')->nullable(); // ID Guru/Wali Kelas
            $table->dateTime('approval_date')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes
            $table->index('siswa_id', 'idx_siswa_izin');
            $table->index('status', 'idx_status_izin');
            
            // Foreign Key
            $table->foreign('siswa_id', 'fk_izin_siswa')
                  ->references('id_siswa')
                  ->on('siswa')
                  ->onDelete('cascade');
        });

        // Tabel Pesan/Konsultasi (Chat)
        Schema::create('pesan', function (Blueprint $table) {
            $table->id('id_pesan');
            $table->unsignedBigInteger('pengirim_id'); // User ID
            $table->unsignedBigInteger('penerima_id'); // User ID
            $table->string('subjek', 200)->nullable();
            $table->text('isi_pesan');
            $table->string('attachment', 255)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); // Untuk Thread/Reply
            $table->boolean('is_read')->default(false);
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes
            $table->index(['pengirim_id', 'penerima_id'], 'idx_chat_user');
            
            // Foreign Keys
            $table->foreign('pengirim_id', 'fk_pesan_sender')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
                  
            $table->foreign('penerima_id', 'fk_pesan_receiver')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        // Tabel Notifikasi (Push Notification History)
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->unsignedBigInteger('user_id');
            $table->string('judul', 100);
            $table->text('pesan');
            $table->enum('tipe', ['Info', 'Akademik', 'Keuangan', 'Presensi', 'Pesan']);
            $table->string('link_url', 255)->nullable(); // Deeplink ke aplikasi
            $table->boolean('is_read')->default(false);
            $table->timestamp('created_at')->useCurrent();
            
            // Index
            $table->index('user_id', 'idx_notif_user');
            
            // Foreign Key
            $table->foreign('user_id', 'fk_notif_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('pesan');
        Schema::dropIfExists('perizinan');
    }
};
