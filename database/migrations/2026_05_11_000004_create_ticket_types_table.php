<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_types', function (Blueprint $table) {
            // Identity
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();

            // Ticket info
            $table->string('name');
            $table->text('description')->nullable();

            // Pricing
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('kes');

            // Inventory
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('sold')->default(0);

            // Purchase limits
            $table->unsignedSmallInteger('min_per_order')->default(1);
            $table->unsignedSmallInteger('max_per_order')->default(10);

            // Sales window
            $table->dateTime('sales_start')->nullable();
            $table->dateTime('sales_end')->nullable();

            // Visibility + ordering
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};
