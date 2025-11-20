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
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->string('midtrans_order_id', 100)->nullable()->after('nomor_va');
            $table->string('midtrans_transaction_id', 100)->nullable()->after('midtrans_order_id');
            $table->string('midtrans_payment_type', 50)->nullable()->after('midtrans_transaction_id');
            $table->enum('midtrans_transaction_status', ['pending', 'settlement', 'expire', 'cancel', 'deny'])->nullable()->after('midtrans_payment_type');
            $table->text('midtrans_response')->nullable()->after('midtrans_transaction_status');
            $table->timestamp('midtrans_paid_at')->nullable()->after('midtrans_response');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_order_id',
                'midtrans_transaction_id',
                'midtrans_payment_type',
                'midtrans_transaction_status',
                'midtrans_response',
                'midtrans_paid_at'
            ]);
        });
    }
};
