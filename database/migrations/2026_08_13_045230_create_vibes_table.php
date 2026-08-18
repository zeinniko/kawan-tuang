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
        // 1. Buat tabel induk vibes
        Schema::create('vibes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('icon_emoji', 10)->nullable();
            $table->timestamps();
        });

        // 2. Buat tabel pivot product_vibes
        Schema::create('product_vibes', function (Blueprint $table) {
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('vibe_id')->constrained('vibes')->cascadeOnDelete();
            $table->primary(['product_id', 'vibe_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus tabel pivot terlebih dahulu agar tidak terjadi error Foreign Key Constraint
        Schema::dropIfExists('product_vibes');
        Schema::dropIfExists('vibes');
    }
};