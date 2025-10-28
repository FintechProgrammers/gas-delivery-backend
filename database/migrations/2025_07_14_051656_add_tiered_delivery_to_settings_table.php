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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('delivery_rate_type')->default('per_km');
            $table->json('tiered_rates')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn(['delivery_rate_type', 'tiered_rates']);
            });
        });
    }
};