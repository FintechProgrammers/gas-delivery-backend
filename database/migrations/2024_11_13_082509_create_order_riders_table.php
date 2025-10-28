<?php

use App\Models\GasOrder;
use App\Models\User;
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
        Schema::create('order_riders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'rider_id');
            $table->foreignIdFor(GasOrder::class, 'order_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_riders');
    }
};
