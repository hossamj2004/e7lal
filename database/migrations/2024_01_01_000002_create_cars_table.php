<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('brand'); // الماركة
            $table->string('model'); // الموديل
            $table->integer('year'); // سنة الصنع
            $table->string('color')->nullable(); // اللون
            $table->integer('mileage')->default(0); // الكيلومترات
            $table->string('fuel_type'); // نوع الوقود
            $table->string('transmission'); // ناقل الحركة
            $table->decimal('price', 12, 2); // السعر
            $table->text('description')->nullable(); // الوصف
            $table->string('image')->nullable(); // صورة العربية
            $table->enum('status', ['available', 'reserved', 'sold'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};


