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
        Schema::create('users', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name', 200);
            $table->string('email', 200)->unique('uk_email');
            $table->string('password');
            $table->enum('role', ['admin', 'guru', 'siswa'])->index('idx_role');
            $table->bigInteger('reference_id')->nullable()->index('idx_reference');
            $table->dateTime('last_login')->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            $table->boolean('must_change_password')->nullable()->default(true);
            $table->string('db_user', 100)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
