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
        Schema::table('providers', function (Blueprint $table) {
            $table->uuid('uuid')->after('id');
            $table->string('name')->after('uuid');
            $table->string('short_name')->after('name');
            $table->boolean('is_default')->default(false)->after('short_name');
            $table->boolean('has_transaction')->default(false)->after('is_default');
            $table->boolean('has_account')->default(false)->after('has_transaction');
            $table->boolean('is_active')->default(false)->after('has_account');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('providerrs', function (Blueprint $table) {
            //
        });
    }
};
