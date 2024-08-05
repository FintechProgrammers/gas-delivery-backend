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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('user_id');
            $table->foreignId('payment_method_id')->nullable();
            $table->string('reference');
            $table->json('sender_information')->nullable();
            $table->json('receiver_information')->nullable();
            $table->json('pickup_address')->nullable();
            $table->json('destination_address')->nullable();
            $table->bigInteger('distance_covered')->default(0);
            $table->json('items')->nullable();
            $table->bigInteger('amount_per_distance')->default(0);
            $table->bigInteger('total_amount')->default(0);
            $table->boolean('is_paid')->default(false);
            $table->enum('status', \App\Models\Delivery::STATUS)->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
