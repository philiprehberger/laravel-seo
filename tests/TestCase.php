<?php

declare(strict_types=1);

namespace PhilipRehberger\Seo\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use PhilipRehberger\Seo\SeoServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            SeoServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.name', 'Test App');
        $app['config']->set('app.url', 'http://localhost');
        $app['config']->set('laravel-seo.default_title', 'Test App');
        $app['config']->set('laravel-seo.default_description', 'Test description');
        $app['config']->set('laravel-seo.site_name', 'Test App');
        $app['config']->set('laravel-seo.locale', 'en_US');
        $app['config']->set('laravel-seo.og_image', null);
        $app['config']->set('laravel-seo.og_type', 'website');
        $app['config']->set('laravel-seo.twitter_handle', null);
        $app['config']->set('laravel-seo.organization', [
            'name' => null,
            'url' => null,
            'logo' => null,
            'email' => null,
            'description' => null,
            'founding_date' => null,
            'same_as' => [],
        ]);
        $app['config']->set('laravel-seo.pages', []);
    }
}
