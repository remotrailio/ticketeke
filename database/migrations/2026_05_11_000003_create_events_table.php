<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            // Identity
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organizer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained();

            // Content
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt')->nullable();
            $table->longText('description')->nullable();

            // Media
            $table->string('banner_image')->nullable();

            // Location
            $table->string('venue_name')->nullable();
            $table->string('venue_address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Online
            $table->boolean('is_online')->default(false);
            $table->string('meeting_url')->nullable();

            // Time
            $table->string('timezone')->default('Africa/Nairobi');
            $table->dateTime('start_at');
            $table->dateTime('end_at');

            // Publishing
            $table->enum('visibility', ['public', 'private', 'unlisted'])->default('public');
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->dateTime('published_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
