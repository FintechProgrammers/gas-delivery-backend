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
        Schema::create('gas_pricings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('business_id')->index();
            $table->bigInteger('price')->nullable();
            $table->bigInteger('kg')->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gas_pricings');
    }
};
