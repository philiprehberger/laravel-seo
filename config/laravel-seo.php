<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Title
    |--------------------------------------------------------------------------
    |
    | The default page title used when no title is explicitly set. Falls back
    | to the application name defined in your .env file.
    |
    */

    'default_title' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Default Description
    |--------------------------------------------------------------------------
    |
    | The default meta description used when no description is explicitly set.
    |
    */

    'default_description' => '',

    /*
    |--------------------------------------------------------------------------
    | Site Name
    |--------------------------------------------------------------------------
    |
    | The site name displayed in Open Graph meta tags (og:site_name).
    |
    */

    'site_name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Locale
    |--------------------------------------------------------------------------
    |
    | The locale used in Open Graph meta tags (og:locale). Should be in the
    | format of language_TERRITORY (e.g. en_US, de_DE, fr_FR).
    |
    */

    'locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Default Open Graph Image
    |--------------------------------------------------------------------------
    |
    | The default image used for Open Graph and Twitter Card meta tags.
    | Can be an absolute URL or a relative path (will be converted automatically).
    |
    */

    'og_image' => null,

    /*
    |--------------------------------------------------------------------------
    | Default Open Graph Type
    |--------------------------------------------------------------------------
    |
    | The default Open Graph type. Common values: website, article, product.
    |
    */

    'og_type' => 'website',

    /*
    |--------------------------------------------------------------------------
    | Twitter Handle
    |--------------------------------------------------------------------------
    |
    | Your Twitter/X username including the @ symbol (e.g. @yourbrand).
    | Used for the twitter:site and twitter:creator meta tags.
    |
    */

    'twitter_handle' => null,

    /*
    |--------------------------------------------------------------------------
    | Organization Schema
    |--------------------------------------------------------------------------
    |
    | Configuration for the JSON-LD Organization schema automatically included
    | in every page's structured data.
    |
    */

    'organization' => [
        'name' => null,
        'url' => null,
        'logo' => null,
        'email' => null,
        'description' => null,
        'founding_date' => null,
        'same_as' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Page-Specific SEO
    |--------------------------------------------------------------------------
    |
    | Define SEO metadata for specific pages by key. Load them in your
    | controllers or views using Seo::forPage('home').
    |
    | Example:
    |   'pages' => [
    |       'home' => [
    |           'title' => 'Welcome to My Site',
    |           'description' => 'The best site on the web.',
    |       ],
    |       'about' => [
    |           'title' => 'About Us',
    |           'description' => 'Learn more about our team.',
    |       ],
    |   ],
    |
    */

    'pages' => [],

];
