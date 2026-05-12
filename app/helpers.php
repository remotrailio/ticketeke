<?php

use App\Models\Setting;

if (! function_exists('app_settings')) {
    function app_settings(): Setting
    {
        $attrs = cache()->remember(
            'app_settings',
            now()->addHour(),
            fn () => Setting::instance()->getAttributes()
        );

        return (new Setting())->forceFill($attrs)->syncOriginal();
    }
}

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return app_settings()->{$key} ?? $default;
    }
}
