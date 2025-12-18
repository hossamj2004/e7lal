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
        Schema::table('cars', function (Blueprint $table) {
            // Drop the old image field
            $table->dropColumn('image');
            // Add new images field as JSON for multiple image URLs
            $table->json('images')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Drop the new images field
            $table->dropColumn('images');
            // Restore the old image field
            $table->string('image')->nullable();
        });
    }
};
