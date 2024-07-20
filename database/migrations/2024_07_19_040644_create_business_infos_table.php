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
        Schema::create('business_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('business_name')->nullable();
            $table->string('date_incorporated')->nullable();
            $table->longText('office_address')->nullable();
            $table->longText('longitude')->nullable();
            $table->longText('latitude')->nullable();
            $table->longText('opening_hours')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_infos');
    }
};
