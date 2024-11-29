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
        Schema::table('delivery_addresses', function (Blueprint $table) {
            // Drop old columns first
            $table->dropColumn(['country_id', 'state', 'city', 'house_number', 'street', 'nearest_land_mark']);

            // Add new columns
            $table->text('address')->nullable()->after('user_id');
            $table->string('longitude')->nullable()->after('address');
            $table->string('latitude')->nullable()->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_addresses', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['address', 'longitude', 'latitude']);
        });
    }
};
