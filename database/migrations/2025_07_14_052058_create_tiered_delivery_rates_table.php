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
        Schema::create('tiered_delivery_rates', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_distance', 8, 2); // e.g., 1.00 km
            $table->decimal('max_distance', 8, 2); // e.g., 10.00 km
            $table->decimal('price', 10, 2);       // e.g., NGN 1000.00
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiered_delivery_rates');
    }
};