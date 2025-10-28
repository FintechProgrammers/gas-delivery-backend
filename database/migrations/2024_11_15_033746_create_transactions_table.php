<?php

use App\Models\Admin;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignIdFor(User::class, 'user_id');
            $table->foreignIdFor(Admin::class, 'admin_id')->nullable();
            $table->foreignId('provider_id')->nullable();
            $table->foreignId('order_id')->nullable();
            $table->string('reference');
            $table->string('external_reference')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('opening_balance', 10, 2)->default(0);
            $table->decimal('closing_balance', 10, 2)->default(0);
            $table->enum('type', ['credit', 'debit']);
            $table->enum('action', ['withdrawal', 'deposit', 'purchase', 'reversal']);
            $table->enum('status', ['pending', 'completed', 'failed']);
            $table->longText('request_payload')->nullable();
            $table->longText('response_payload')->nullable();
            $table->longText('receiver_informations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
