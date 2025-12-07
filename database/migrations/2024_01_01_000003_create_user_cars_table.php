<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('brand'); // الماركة
            $table->string('model'); // الموديل
            $table->integer('year'); // سنة الصنع
            $table->string('color')->nullable(); // اللون
            $table->integer('mileage')->default(0); // الكيلومترات
            $table->string('fuel_type'); // نوع الوقود
            $table->string('transmission'); // ناقل الحركة
            $table->decimal('user_expected_price', 12, 2); // السعر المتوقع من المستخدم
            $table->decimal('fair_price', 12, 2)->nullable(); // السعر العادل (يحدده الأدمن)
            $table->text('description')->nullable(); // الوصف
            $table->string('image')->nullable(); // صورة العربية
            $table->enum('status', ['pending', 'priced', 'rejected'])->default('pending');
            $table->boolean('is_active')->default(false); // العربية النشطة للتبديل
            $table->text('admin_notes')->nullable(); // ملاحظات الأدمن
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_cars');
    }
};


