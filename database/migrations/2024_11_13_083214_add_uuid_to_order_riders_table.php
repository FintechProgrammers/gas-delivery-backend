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
        Schema::table('order_riders', function (Blueprint $table) {
            $table->uuid('uuid')->after('id');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending')->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_riders', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'status']);
        });
    }
};
