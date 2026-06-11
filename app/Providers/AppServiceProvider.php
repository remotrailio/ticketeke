<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::replaceNamespace('mail', app(Markdown::class)->htmlComponentPaths());

        if ($override = config('mail.to_override')) {
            Mail::alwaysTo($override);
        }

        // Guard against fresh environments where migrations have not run yet.
        // package:discover and other artisan bootstrap commands hit boot() before
        // the database is ready, so we fall back to an empty Setting instance.
        try {
            View::share('__settings', app_settings());
        } catch (\Throwable) {
            View::share('__settings', new Setting());
        }
    }
}
