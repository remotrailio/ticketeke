<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            // Identity
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('order_number')->unique();

            // Relationships
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();

            // Pricing
            $table->decimal('subtotal', 10, 2);
            $table->decimal('fees', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('currency', 3)->default('kes');

            // Order state
            $table->enum('status', ['pending', 'completed', 'cancelled', 'expired', 'refunded'])
                ->default('pending');
            $table->enum('payment_status', ['unpaid', 'processing', 'paid', 'failed', 'refunded'])
                ->default('unpaid');

            // Payment details
            $table->string('payment_provider')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('payment_method')->nullable();

            // Timing
            $table->dateTime('expires_at')->nullable();
            $table->dateTime('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
