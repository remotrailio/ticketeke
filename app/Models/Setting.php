<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = ['app_name', 'logo', 'favicon'];

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo
            ? Storage::disk('r2')->url($this->logo)
            : null;
    }

    public function getFaviconUrlAttribute(): ?string
    {
        return $this->favicon
            ? Storage::disk('r2')->url($this->favicon)
            : null;
    }

    public static function instance(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'app_name' => config('app.name', 'Pitisha'),
        ]);
    }
}
