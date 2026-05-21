<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['uuid', 'name', 'image_path'])]
class Destination extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Destination $destination) {
            $destination->uuid ??= (string) Str::uuid();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
