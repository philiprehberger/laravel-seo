<?php

declare(strict_types=1);

namespace PhilipRehberger\Seo\Tests;

use PhilipRehberger\Seo\SeoService;

class SeoServiceTest extends TestCase
{
    private SeoService $seo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seo = new SeoService;
    }

    public function test_get_title_returns_default_when_not_set(): void
    {
        $this->assertSame('Test App', $this->seo->getTitle());
    }

    public function test_set_title_overrides_default(): void
    {
        $this->seo->setTitle('Custom Title');

        $this->assertSame('Custom Title', $this->seo->getTitle());
    }

    public function test_get_description_returns_default(): void
    {
        $this->assertSame('Test description', $this->seo->getDescription());
    }

    public function test_set_description_overrides_default(): void
    {
        $this->seo->setDescription('Custom description');

        $this->assertSame('Custom description', $this->seo->getDescription());
    }

    public function test_get_canonical_returns_current_url(): void
    {
        $canonical = $this->seo->getCanonical();

        $this->assertNotEmpty($canonical);
        $this->assertStringStartsWith('http', $canonical);
    }

    public function test_set_canonical_overrides(): void
    {
        $this->seo->setCanonical('https://example.com/page');

        $this->assertSame('https://example.com/page', $this->seo->getCanonical());
    }

    public function test_get_og_image_converts_relative_to_absolute(): void
    {
        $this->seo->setOgImage('/images/og.jpg');
        $image = $this->seo->getOgImage();

        $this->assertStringStartsWith('http', $image);
        $this->assertStringContainsString('/images/og.jpg', $image);
    }

    public function test_set_og_type(): void
    {
        $this->seo->setOgType('article');

        $this->assertSame('article', $this->seo->getOgType());
    }

    public function test_noindex_defaults_false(): void
    {
        $this->assertFalse($this->seo->isNoindex());
    }

    public function test_set_noindex(): void
    {
        $this->seo->setNoindex(true);

        $this->assertTrue($this->seo->isNoindex());
    }

    public function test_add_json_ld_accumulates_schemas(): void
    {
        $this->seo->addJsonLd(['@type' => 'WebPage', 'name' => 'Home']);
        $this->seo->addJsonLd(['@type' => 'BreadcrumbList']);

        $schemas = $this->seo->getJsonLd();

        $this->assertCount(2, $schemas);
        $this->assertSame('WebPage', $schemas[0]['@type']);
        $this->assertSame('BreadcrumbList', $schemas[1]['@type']);
    }

    public function test_get_site_name_from_config(): void
    {
        $this->assertSame('Test App', $this->seo->getSiteName());
    }

    public function test_get_locale_from_config(): void
    {
        $this->assertSame('en_US', $this->seo->getLocale());
    }

    public function test_get_twitter_handle_from_config(): void
    {
        $handle = $this->seo->getTwitterHandle();

        $this->assertSame('', $handle);
    }

    public function test_for_page_loads_from_config(): void
    {
        config()->set('laravel-seo.pages', [
            'home' => [
                'title' => 'Home Page',
                'description' => 'Welcome to our home page.',
            ],
        ]);

        $this->seo->forPage('home');

        $this->assertSame('Home Page', $this->seo->getTitle());
        $this->assertSame('Welcome to our home page.', $this->seo->getDescription());
    }

    public function test_get_organization_schema(): void
    {
        config()->set('laravel-seo.organization', [
            'name' => 'Acme Corp',
            'url' => 'https://acme.com',
            'email' => 'hello@acme.com',
            'description' => 'We build great things.',
            'founding_date' => '2020',
            'same_as' => ['https://twitter.com/acme'],
            'logo' => null,
        ]);

        $schema = $this->seo->getOrganizationSchema();

        $this->assertSame('https://schema.org', $schema['@context']);
        $this->assertSame('Organization', $schema['@type']);
        $this->assertSame('Acme Corp', $schema['name']);
        $this->assertSame('https://acme.com', $schema['url']);
        $this->assertSame('We build great things.', $schema['description']);
        $this->assertSame('2020', $schema['foundingDate']);
        $this->assertSame(['https://twitter.com/acme'], $schema['sameAs']);
        $this->assertArrayHasKey('contactPoint', $schema);
        $this->assertSame('hello@acme.com', $schema['contactPoint']['email']);
    }

    public function test_get_website_schema(): void
    {
        $schema = $this->seo->getWebsiteSchema();

        $this->assertSame('https://schema.org', $schema['@context']);
        $this->assertSame('WebSite', $schema['@type']);
        $this->assertSame('Test App', $schema['name']);
        $this->assertArrayHasKey('url', $schema);
    }

    public function test_get_service_schema(): void
    {
        $schema = $this->seo->getServiceSchema('Web Design', 'We design websites.', 'Design');

        $this->assertSame('https://schema.org', $schema['@context']);
        $this->assertSame('Service', $schema['@type']);
        $this->assertSame('Web Design', $schema['name']);
        $this->assertSame('We design websites.', $schema['description']);
        $this->assertSame('Design', $schema['serviceType']);
        $this->assertSame('Organization', $schema['provider']['@type']);
        $this->assertSame('Test App', $schema['provider']['name']);
        $this->assertSame('Worldwide', $schema['areaServed']);
    }

    public function test_get_breadcrumb_schema(): void
    {
        $items = [
            ['name' => 'Home', 'url' => 'https://example.com/'],
            ['name' => 'Blog', 'url' => 'https://example.com/blog'],
            ['name' => 'Post', 'url' => 'https://example.com/blog/post'],
        ];

        $schema = $this->seo->getBreadcrumbSchema($items);

        $this->assertSame('https://schema.org', $schema['@context']);
        $this->assertSame('BreadcrumbList', $schema['@type']);
        $this->assertCount(3, $schema['itemListElement']);
        $this->assertSame(1, $schema['itemListElement'][0]['position']);
        $this->assertSame('Home', $schema['itemListElement'][0]['name']);
        $this->assertSame(3, $schema['itemListElement'][2]['position']);
        $this->assertSame('Post', $schema['itemListElement'][2]['name']);
    }

    public function test_reset_clears_all_values(): void
    {
        $this->seo->setTitle('Custom Title')
            ->setDescription('Custom description')
            ->setCanonical('https://example.com/page')
            ->setOgImage('https://example.com/image.jpg')
            ->setOgType('article')
            ->setNoindex(true)
            ->addJsonLd(['@type' => 'WebPage']);

        $this->seo->reset();

        $this->assertSame('Test App', $this->seo->getTitle());
        $this->assertSame('Test description', $this->seo->getDescription());
        $this->assertFalse($this->seo->isNoindex());
        $this->assertEmpty($this->seo->getJsonLd());
        $this->assertSame('website', $this->seo->getOgType());
    }
}
