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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('gateway', 30)->default('sslcommerz');
            $table->string('transaction_id', 100)->nullable()->unique();
            $table->string('val_id', 100)->nullable()->unique();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('BDT');
            $table->enum('status', ['initiated', 'success', 'failed', 'cancelled'])->default('initiated')->index();
            $table->string('card_type', 50)->nullable();
            $table->string('bank_tran_id', 100)->nullable();
            $table->decimal('store_amount', 12, 2)->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
