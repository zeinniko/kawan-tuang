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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('order_number', 50)->unique();
            $table->foreignUuid('user_id')->constrained('users');
            $table->foreignUuid('store_id')->nullable()->constrained('stores');
            $table->foreignUuid('voucher_id')->nullable()->constrained('vouchers');
            
            $table->enum('fulfillment_type', ['delivery', 'pickup']);
            $table->string('pickup_code', 10)->nullable();
            $table->text('pickup_qr_url')->nullable();
            
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('delivery_fee', 12, 2)->default(0);
            $table->decimal('admin_fee', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            
            $table->enum('status', ['pending_payment', 'paid', 'processing', 'delivering', 'completed', 'cancelled'])->default('pending_payment');
            $table->json('address_snapshot');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
