<?php

namespace App\Enums;

enum Country: string
{
    case KENYA = 'KE';

    public function label(): string
    {
        return match ($this) {
            self::KENYA => 'Kenya',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $country) => [$country->value => $country->label()])
            ->all();
    }
}
