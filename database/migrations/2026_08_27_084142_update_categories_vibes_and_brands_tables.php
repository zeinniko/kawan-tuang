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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('icon_url')->nullable()->after('slug');
        });

        Schema::table('vibes', function (Blueprint $table) {
            if (Schema::hasColumn('vibes', 'icon_emoji')) {
                $table->dropColumn('icon_emoji');
            }
            $table->string('slug')->nullable()->unique()->after('name');
            $table->string('icon_url')->nullable()->after('slug');
            $table->string('image_url')->nullable()->after('icon_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('icon_url');
        });

        Schema::table('vibes', function (Blueprint $table) {
            $table->string('icon_emoji')->nullable();
            $table->dropColumn(['slug', 'icon_url', 'image_url']);
        });
    }
};
