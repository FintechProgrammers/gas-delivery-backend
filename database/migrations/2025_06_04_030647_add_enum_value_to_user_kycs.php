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
        Schema::table('user_kycs', function (Blueprint $table) {
            $table->enum('service', ['nin', 'drivers_license', 'voters_card', 'cac', 'passport', 'bvn'])->change();
        });

        if (Schema::hasColumn('user_kycs', 'statis')) {
            Schema::table('user_kycs', function (Blueprint $table) {
                $table->dropColumn('statis');
            });
        }

        if (!Schema::hasColumn('user_kycs', 'status')) {
            Schema::table('user_kycs', function (Blueprint $table) {
                $table->enum('status', ['pending', 'approved', 'declined'])->after('service');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_kycs', function (Blueprint $table) {
            //
        });
    }
};
