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
        Schema::create('gas_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('business_id');
            $table->foreignId('delivery_address_id');
            $table->foreignId('payment_method_id')->nullable();
            $table->string('reference');
            $table->bigInteger('delivery_fee')->default(0);
            $table->bigInteger('price_per_kg')->default(0);
            $table->bigInteger('service_charge')->default(0);
            $table->bigInteger('total_amount')->default(0);
            $table->boolean('is_paid')->default(false);
            $table->enum('status', \App\Models\GasOrder::STATUS)->default(\App\Models\GasOrder::STATUS['pending']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gas_orders');
    }
};
