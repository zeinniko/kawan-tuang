<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['earn', 'redeem', 'adjustment']);
            $table->integer('amount'); // Positif jika penambahan, negatif jika pengurangan
            $table->unsignedInteger('balance_after');
            $table->string('description')->nullable();
            $table->string('reference_id')->nullable(); // Misal: order_id
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_logs');
    }
};