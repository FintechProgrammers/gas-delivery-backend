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
        Schema::table('gas_orders', function (Blueprint $table) {
            $table->string('cylinder_size')->nullable()->after('gas_size');
            $table->string('price_per_km')->nullable()->after('cylinder_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gas_orders', function (Blueprint $table) {
            $table->dropColumn(['cylinder_size', 'price_per_km']);
        });
    }
};
