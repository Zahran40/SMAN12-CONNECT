<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk tracking pengumuman yang sudah dibaca (read receipt)
     */
    public function up(): void
    {
        Schema::create('pengumuman_dibaca', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('pengumuman_id')->index('idx_pengumuman');
            $table->bigInteger('user_id')->index('idx_user');
            $table->timestamp('dibaca_pada')->useCurrent();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            
            // Unique: satu user hanya bisa baca satu pengumuman sekali
            $table->unique(['pengumuman_id', 'user_id'], 'uk_pengumuman_user');

            // Foreign keys
            $table->foreign(['pengumuman_id'], 'fk_dibaca_pengumuman')
                ->references(['id_pengumuman'])->on('pengumuman')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign(['user_id'], 'fk_dibaca_user')
                ->references(['id'])->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        // Update tabel pengumuman untuk workflow approval
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->enum('status_approval', ['Draft', 'Menunggu Approval', 'Disetujui', 'Ditolak'])
                ->default('Draft')->after('status')->index('idx_approval');
            $table->bigInteger('disetujui_oleh')->nullable()->after('status_approval')
                ->comment('User ID Kepala Sekolah yang approve');
            $table->timestamp('tanggal_approval')->nullable()->after('disetujui_oleh');
            $table->text('catatan_approval')->nullable()->after('tanggal_approval');
            
            // Foreign key
            $table->foreign(['disetujui_oleh'], 'fk_pengumuman_approver')
                ->references(['id'])->on('users')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->dropForeign(['disetujui_oleh']);
            $table->dropColumn(['status_approval', 'disetujui_oleh', 'tanggal_approval', 'catatan_approval']);
        });
        
        Schema::dropIfExists('pengumuman_dibaca');
    }
};
