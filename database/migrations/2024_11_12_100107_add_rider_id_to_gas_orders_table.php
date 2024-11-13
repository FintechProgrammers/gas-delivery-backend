<?php

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
        Schema::table('gas_orders', function (Blueprint $table) {
            $table->foreignIdFor(User::class, 'rider_id')->nullable()->after('business_id');
            $table->decimal('gas_amount', 10, 2)->default(0)->after('total_amount');
            $table->decimal('distance', 10, 2)->default(0)->after('gas_amount');
            $table->longText('from_distination')->nullable()->after('status');
            $table->longText('to_distination')->nullable()->after('from_distination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gas_orders', function (Blueprint $table) {
            $table->dropColumn(['rider_id', 'gas_amount']);
        });
    }
};
