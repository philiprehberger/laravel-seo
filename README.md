# Laravel SEO

A fluent SEO metadata service for Laravel with Open Graph, Twitter Card, and JSON-LD structured data support.

## Requirements

- PHP 8.2+
- Laravel 11 or 12

## Installation

```bash
composer require philiprehberger/laravel-seo
```

### Publish the config file

```bash
php artisan vendor:publish --tag=laravel-seo-config
```

This creates `config/laravel-seo.php` in your application.

### Optionally publish the Blade views

```bash
php artisan vendor:publish --tag=laravel-seo-views
```

## Configuration

`config/laravel-seo.php`:

```php
return [
    'default_title'       => env('APP_NAME', 'Laravel'),
    'default_description' => '',
    'site_name'           => env('APP_NAME', 'Laravel'),
    'locale'              => 'en_US',
    'og_image'            => null,
    'og_type'             => 'website',
    'twitter_handle'      => '@yourbrand',

    'organization' => [
        'name'         => 'Acme Corp',
        'url'          => 'https://acme.com',
        'logo'         => '/images/logo.png',
        'email'        => 'hello@acme.com',
        'description'  => 'We build great things.',
        'founding_date'=> '2020',
        'same_as'      => [
            'https://twitter.com/acme',
            'https://linkedin.com/company/acme',
        ],
    ],

    'pages' => [
        'home' => [
            'title'       => 'Welcome to Acme',
            'description' => 'The best products on the web.',
        ],
        'about' => [
            'title'       => 'About Us',
            'description' => 'Learn more about our team.',
        ],
    ],
];
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

// In a controller or service provider
Seo::setTitle('My Page')
   ->setDescription('Welcome to my page.')
   ->setCanonical('https://example.com/page')
   ->setOgImage('https://example.com/og.jpg')
   ->setOgType('article')
   ->setNoindex(false);
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

Load predefined SEO data for a page by its config key:

```php
Seo::forPage('home');
// or
app(SeoService::class)->forPage('about');
```

### JSON-LD Structured Data

#### Add a custom schema

```php
Seo::addJsonLd([
    '@context' => 'https://schema.org',
    '@type'    => 'Article',
    'headline' => 'My Blog Post',
    'author'   => ['@type' => 'Person', 'name' => 'Jane Doe'],
]);
```

#### Built-in schema generators

**Organization schema** (also rendered automatically when no other JSON-LD is set):

```php
$schema = Seo::getOrganizationSchema();
Seo::addJsonLd($schema);
```

**WebSite schema**:

```php
Seo::addJsonLd(Seo::getWebsiteSchema());
```

**Service schema**:

```php
Seo::addJsonLd(
    Seo::getServiceSchema('Web Design', 'We design beautiful websites.', 'Design')
);
```

**BreadcrumbList schema**:

```php
Seo::addJsonLd(Seo::getBreadcrumbSchema([
    ['name' => 'Home',    'url' => 'https://example.com/'],
    ['name' => 'Blog',    'url' => 'https://example.com/blog'],
    ['name' => 'My Post', 'url' => 'https://example.com/blog/my-post'],
]));
```

### Resetting Between Requests

The service is registered as a singleton. If you need to reset its state (e.g. in tests or when reusing across requests):

```php
Seo::reset();
```

### CSP Nonce Support

If your application uses a Content Security Policy with script nonces, pass `$cspNonce` to your view and the package will automatically apply it to all JSON-LD `<script>` tags:

```blade
{{-- In your layout --}}
<x-seo::meta />
```

The component checks for `$cspNonce` in the view scope. If it is set, it renders `<script nonce="{{ $cspNonce }}" ...>`. If it is not set, the nonce attribute is omitted entirely, so the package works out of the box without any security-headers package.

## Rendered Output

The Blade component produces:

```html
<title>My Page</title>
<meta name="description" content="Welcome to my page.">
<meta name="robots" content="index, follow">

<link rel="canonical" href="https://example.com/page">

<meta property="og:type" content="website">
<meta property="og:url" content="https://example.com/page">
<meta property="og:title" content="My Page">
<meta property="og:description" content="Welcome to my page.">
<meta property="og:site_name" content="My App">
<meta property="og:locale" content="en_US">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@yourbrand">
<meta name="twitter:creator" content="@yourbrand">
<meta name="twitter:title" content="My Page">
<meta name="twitter:description" content="Welcome to my page.">

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "My App",
    "url": "https://example.com"
}
</script>
```

## Testing

```bash
composer test
```

## Code Style

```bash
./vendor/bin/pint
```

## License

The MIT License (MIT). See [LICENSE](LICENSE) for details.


