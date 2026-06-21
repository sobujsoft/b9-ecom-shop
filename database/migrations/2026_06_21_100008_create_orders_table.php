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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 32)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name', 150);
            $table->string('phone', 20)->index();
            $table->string('email');
            $table->string('district', 100);
            $table->string('area', 150);
            $table->text('address');
            $table->text('notes')->nullable();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->enum('payment_method', ['cod', 'sslcommerz']);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending')->index();
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending')->index();
            $table->timestamp('placed_at')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
