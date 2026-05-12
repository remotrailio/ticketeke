<?php

namespace App\Models;

use App\Enums\OrganizerStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable(['user_id', 'display_name', 'slug', 'bio', 'logo', 'email', 'phone', 'verified', 'status'])]
class Organizer extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Organizer $organizer) {
            $organizer->uuid ??= (string) Str::uuid();

            if (empty($organizer->slug)) {
                $base = Str::slug($organizer->display_name);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $organizer->slug = $slug;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'verified' => 'boolean',
            'status' => OrganizerStatus::class,
        ];
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo
            ? Storage::disk('r2')->url($this->logo)
            : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(\App\Models\Event::class);
    }
}
