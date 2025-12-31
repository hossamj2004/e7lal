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
        Schema::table('exchange_requests', function (Blueprint $table) {
            $table->boolean('is_favorite')->default(false)->after('user_id');
            $table->text('admin_notes')->nullable()->after('is_favorite');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending')->after('admin_notes');
            $table->timestamp('responded_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exchange_requests', function (Blueprint $table) {
            $table->dropColumn(['is_favorite', 'admin_notes', 'status', 'responded_at']);
        });
    }
};