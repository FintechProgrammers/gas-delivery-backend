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
        Schema::table('user_infos', function (Blueprint $table) {
            $table->string('country_code')->nullable()->after('user_id');
            $table->text('address')->nullable()->after('country_code');
            $table->string('state')->nullable()->after('address');
            $table->string('city')->nullable()->after('state');
            $table->string('date_of_birth')->nullable()->after('city');
            $table->integer('zip_code')->nullable()->after('date_of_birth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_infos', function (Blueprint $table) {
            $table->dropColumn('country_id', 'address', 'state', 'city', 'date_of_birth', 'zip_code');
        });
    }
};
