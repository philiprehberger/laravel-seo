# Laravel SEO

[![Tests](https://github.com/philiprehberger/laravel-seo/actions/workflows/tests.yml/badge.svg)](https://github.com/philiprehberger/laravel-seo/actions/workflows/tests.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/philiprehberger/laravel-seo.svg)](https://packagist.org/packages/philiprehberger/laravel-seo)
[![Last updated](https://img.shields.io/github/last-commit/philiprehberger/laravel-seo)](https://github.com/philiprehberger/laravel-seo/commits/main)

Fluent SEO metadata service for Laravel with Open Graph, Twitter Card, and JSON-LD structured data support.

## Requirements

- PHP 8.2+
- Laravel 11 or 12

## Installation

```bash
composer require philiprehberger/laravel-seo
```

The service provider is registered automatically via Laravel package auto-discovery.

### Publish the config file

```bash
php artisan vendor:publish --tag=laravel-seo-config
```

This creates `config/laravel-seo.php` in your application.

### Optionally publish the Blade views

```bash
php artisan vendor:publish --tag=laravel-seo-views
```

## Usage

### Blade Component

Add the `<x-seo::meta />` component inside your layout's `<head>` tag:

```blade
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-seo::meta />
</head>
```

The component reads all values from the `SeoService` singleton, but also accepts inline overrides:

```blade
<x-seo::meta
    title="Custom Title"
    description="Custom description"
    canonical="https://example.com/page"
    ogImage="https://example.com/og.jpg"
    ogType="article"
    :noindex="true"
/>
```

### Fluent API via Facade

```php
use PhilipRehberger\Seo\Facades\Seo;

Seo::setTitle('My Page')
   ->setDescription('Welcome to my page.')
   ->setCanonical('https://example.com/page')
   ->setOgImage('https://example.com/og.jpg')
   ->setOgType('article')
   ->setNoindex(false);
```

### Type-Safe Open Graph Types

```php
use PhilipRehberger\Seo\Facades\Seo;
use PhilipRehberger\Seo\OgType;

Seo::setOgType(OgType::Article);
Seo::setOgType(OgType::Product);
Seo::setOgType(OgType::Video);

// Raw strings are still supported
Seo::setOgType('article');
```

### Service Injection

```php
use PhilipRehberger\Seo\SeoService;

class PageController extends Controller
{
    public function show(SeoService $seo): View
    {
        $seo->setTitle('About Us')
            ->setDescription('Learn about our team.');

        return view('about');
    }
}
```

### Page-Specific SEO from Config

```php
Seo::forPage('home');
```

### JSON-LD Structured Data

```php
Seo::addJsonLd([
    '@context' => 'https://schema.org',
    '@type'    => 'Article',
    'headline' => 'My Blog Post',
]);

// Built-in schema generators
Seo::addJsonLd(Seo::getOrganizationSchema());
Seo::addJsonLd(Seo::getWebsiteSchema());
Seo::addJsonLd(Seo::getServiceSchema('Web Design', 'We design beautiful websites.', 'Design'));
Seo::addJsonLd(Seo::getBreadcrumbSchema([
    ['name' => 'Home', 'url' => 'https://example.com/'],
    ['name' => 'Blog', 'url' => 'https://example.com/blog'],
]));
```

### Resetting Between Requests

```php
Seo::reset();
```

## API

| Method | Description |
|--------|-------------|
| `Seo::setTitle(string $title)` | Set the page title |
| `Seo::setDescription(string $description)` | Set the meta description |
| `Seo::setCanonical(string $url)` | Set the canonical URL |
| `Seo::setOgImage(string $url)` | Set the Open Graph image |
| `Seo::setOgType(OgType\|string $type)` | Set the Open Graph type (enum or string) |
| `Seo::setOgImageAlt(string $alt)` | Set the Open Graph image alt text |
| `Seo::setNoindex(bool $noindex)` | Set the noindex flag |
| `Seo::forPage(string $key)` | Load SEO data from config for a page key |
| `Seo::addJsonLd(array $schema)` | Add a JSON-LD structured data block |
| `Seo::getOrganizationSchema()` | Generate an Organization schema array |
| `Seo::getWebsiteSchema()` | Generate a WebSite schema array |
| `Seo::getServiceSchema(string $name, string $description, string $type)` | Generate a Service schema array |
| `Seo::getBreadcrumbSchema(array $items)` | Generate a BreadcrumbList schema array |
| `Seo::reset()` | Reset the service state |

## Development

```bash
composer install
vendor/bin/phpunit
vendor/bin/pint --test
vendor/bin/phpstan analyse
```

## Support

If you find this project useful:

⭐ [Star the repo](https://github.com/philiprehberger/laravel-seo)

🐛 [Report issues](https://github.com/philiprehberger/laravel-seo/issues?q=is%3Aissue+is%3Aopen+label%3Abug)

💡 [Suggest features](https://github.com/philiprehberger/laravel-seo/issues?q=is%3Aissue+is%3Aopen+label%3Aenhancement)

❤️ [Sponsor development](https://github.com/sponsors/philiprehberger)

🌐 [All Open Source Projects](https://philiprehberger.com/open-source-packages)

💻 [GitHub Profile](https://github.com/philiprehberger)

🔗 [LinkedIn Profile](https://www.linkedin.com/in/philiprehberger)

## License

[MIT](LICENSE)
