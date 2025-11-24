<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->bigInteger('id_session', true);
            $table->bigInteger('user_id')->index('idx_user');
            $table->string('session_token')->unique('uk_token');
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('expires_at')->index('idx_expires');
            $table->boolean('is_active')->nullable()->default(true);
            $table->foreign(['user_id'], 'fk_session_user')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};