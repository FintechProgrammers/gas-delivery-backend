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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
            $table->string('business_name')->nullable()->after('last_name');
            $table->string('is_available')->default(true)->after('business_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
