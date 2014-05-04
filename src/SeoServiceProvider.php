<?php

declare(strict_types=1);

namespace PhilipRehberger\Seo;

use Illuminate\Support\ServiceProvider;

class SeoServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-seo.php',
            'laravel-seo'
        );

        $this->app->singleton(SeoService::class, function () {
            return new SeoService;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/laravel-seo.php' => config_path('laravel-seo.php'),
        ], 'laravel-seo-config');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'seo');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/seo'),
        ], 'laravel-seo-views');
    }
}
