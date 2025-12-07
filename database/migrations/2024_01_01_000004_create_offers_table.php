<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_car_id')->constrained()->onDelete('cascade'); // عربية المستخدم
            $table->foreignId('car_id')->constrained()->onDelete('cascade'); // العربية المراد تبديلها
            $table->decimal('offered_difference', 12, 2); // الفرق المعروض من المستخدم
            $table->text('message')->nullable(); // رسالة من المستخدم
            $table->enum('status', ['pending', 'accepted', 'rejected', 'cancelled'])->default('pending');
            $table->text('admin_response')->nullable(); // رد الأدمن
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};


