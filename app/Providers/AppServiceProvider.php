<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Set locale from session or fallback to configured default
        $locale = session('locale') ?? request()->cookie('locale') ?? config('app.locale', 'en');
        
        // Validate against supported locales
        $supported = array_keys(config('languages.supported', []));
        if (in_array($locale, $supported, true)) {
            App::setLocale($locale);
        }
    }
}
