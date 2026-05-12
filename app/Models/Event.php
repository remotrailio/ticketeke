<?php

namespace App\Models;

use App\Enums\EventStatus;
use App\Enums\EventVisibility;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable([
    'organizer_id', 'category_id', 'title', 'slug', 'excerpt', 'description',
    'banner_image', 'venue_name', 'venue_address', 'city', 'country',
    'latitude', 'longitude', 'is_online', 'meeting_url',
    'timezone', 'start_at', 'end_at', 'visibility', 'status', 'published_at',
])]
class Event extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            $event->uuid ??= (string) Str::uuid();

            if (empty($event->slug)) {
                $base = Str::slug($event->title);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $event->slug = $slug;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'is_online'    => 'boolean',
            'start_at'     => 'datetime',
            'end_at'       => 'datetime',
            'published_at' => 'datetime',
            'latitude'     => 'decimal:7',
            'longitude'    => 'decimal:7',
            'visibility'   => EventVisibility::class,
            'status'       => EventStatus::class,
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner_image
            ? Storage::disk('r2')->url($this->banner_image)
            : null;
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(\App\Models\TicketType::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(Ticket::class, Order::class);
    }
}
