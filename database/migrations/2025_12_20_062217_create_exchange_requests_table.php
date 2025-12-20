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
        Schema::create('exchange_requests', function (Blueprint $table) {
            $table->id();
            $table->string('car_model'); // نوع وموديل السيارة
            $table->decimal('car_price', 12, 2); // سعر السيارة
            $table->string('desired_price_range')->nullable(); // رينج السعر المطلوب
            $table->string('location'); // الموقع
            $table->string('ad_link')->nullable(); // لينك الإعلان
            $table->string('phone'); // رقم الهاتف / واتساب
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // ربط مع جدول المستخدمين
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_requests');
    }
};
