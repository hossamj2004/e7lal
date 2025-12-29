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
        // Make car_price nullable in exchange_requests (removed from form)
        Schema::table('exchange_requests', function (Blueprint $table) {
            $table->decimal('car_price', 12, 2)->nullable()->default(null)->change();
        });

        // Make user_expected_price and fair_price nullable in user_cars (no longer required)
        Schema::table('user_cars', function (Blueprint $table) {
            $table->decimal('user_expected_price', 12, 2)->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exchange_requests', function (Blueprint $table) {
            $table->decimal('car_price', 12, 2)->nullable(false)->change();
        });

        Schema::table('user_cars', function (Blueprint $table) {
            $table->decimal('user_expected_price', 12, 2)->nullable(false)->change();
        });
    }
};
