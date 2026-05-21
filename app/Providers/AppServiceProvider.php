<?php

namespace App\Providers;

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

        View::share('__settings', app_settings());
    }
}
