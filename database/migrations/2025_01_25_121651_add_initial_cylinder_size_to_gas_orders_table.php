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
            $table->string('initial_cylinder_size')->nullable()->after('cylinder_size');
            $table->string('final_cylinder_size')->nullable()->after('initial_cylinder_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gas_orders', function (Blueprint $table) {
            $table->dropColumn(['initial_cylinder_size', 'final_cylinder_size']);
        });
    }
};
