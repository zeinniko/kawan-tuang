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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('biteship_order_id', 100)->nullable()->after('status');
            $table->string('courier_company', 50)->nullable()->after('biteship_order_id'); // e.g. gojek, grab, jne
            $table->string('courier_type', 50)->nullable()->after('courier_company');     // e.g. instant, same_day, reg
            $table->string('waybill_number', 100)->nullable()->after('courier_type');
            $table->string('driver_name', 100)->nullable()->after('waybill_number');
            $table->string('driver_phone', 20)->nullable()->after('driver_name');
            $table->text('live_tracking_url')->nullable()->after('driver_phone');
            $table->text('cancel_reason')->nullable()->after('live_tracking_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'biteship_order_id',
                'courier_company',
                'courier_type',
                'waybill_number',
                'driver_name',
                'driver_phone',
                'live_tracking_url',
                'cancel_reason',
            ]);
        });
    }
};
