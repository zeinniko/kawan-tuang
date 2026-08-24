<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_rate_caches', function (Blueprint $table) {
            $table->id();
            
            // Relasi & FK untuk Auto Cascading Delete saat User/Address/Store dihapus
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignUuid('user_address_id')->constrained('user_addresses')->cascadeOnDelete();

            // Hash unik kombinasi koordinat, titik, dan items
            $table->string('cache_key', 64)->unique()->index(); 
            
            // Snapshot titik koordinat untuk deteksi jika titik digeser
            $table->decimal('origin_lat', 10, 8);
            $table->decimal('origin_lng', 11, 8);
            $table->decimal('dest_lat', 10, 8);
            $table->decimal('dest_lng', 11, 8);

            // Simpan hasil response rate JSON
            $table->json('rates_data');
            $table->timestamps();

            // Composite Index untuk pencarian secepat kilat
            $table->index(['user_id', 'store_id', 'user_address_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_rate_caches');
    }
};