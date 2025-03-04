<?php

use App\Models\GasOrder;
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
        Schema::create('order_timelines', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(GasOrder::class, 'order_id'); // Foreign key to orders table
            $table->string('status'); // e.g., pending, confirmed, preparing, out_for_delivery, delivered
            $table->timestamp('status_time')->nullable(); // Timestamp for when the status was updated
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_timelines');
    }
};
