<?php

namespace App\Providers;

use App\Services\AuthService;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AuthService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Number::useLocale('id');
        Number::useCurrency('GBP');
        Blade::componentNamespace('App\\View\\Components', 'ui');
        Blade::anonymousComponentPath(resource_path('views/ui/'), 'ui');
    }
}
