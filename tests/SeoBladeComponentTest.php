<?php

declare(strict_types=1);

namespace PhilipRehberger\Seo\Tests;

use Illuminate\Support\Facades\Blade;
use PhilipRehberger\Seo\SeoService;

class SeoBladeComponentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Reset the SEO service before each test
        app(SeoService::class)->reset();
    }

    private function renderMeta(array $props = []): string
    {
        $propString = '';
        foreach ($props as $key => $value) {
            if (is_bool($value)) {
                $propString .= $value ? " {$key}" : '';
            } elseif (is_array($value)) {
                $encoded = json_encode($value);
                $propString .= " :{$key}='{$encoded}'";
            } else {
                $propString .= " {$key}=\"{$value}\"";
            }
        }

        return Blade::render("<x-seo::meta{$propString} />");
    }

    public function test_component_renders_title(): void
    {
        app(SeoService::class)->setTitle('My Page Title');

        $output = $this->renderMeta();

        $this->assertStringContainsString('<title>My Page Title</title>', $output);
    }

    public function test_component_renders_meta_description(): void
    {
        app(SeoService::class)->setDescription('My page description.');

        $output = $this->renderMeta();

        $this->assertStringContainsString(
            '<meta name="description" content="My page description.">',
            $output
        );
    }

    public function test_component_renders_canonical(): void
    {
        app(SeoService::class)->setCanonical('https://example.com/about');

        $output = $this->renderMeta();

        $this->assertStringContainsString(
            '<link rel="canonical" href="https://example.com/about">',
            $output
        );
    }

    public function test_component_renders_og_tags(): void
    {
        app(SeoService::class)
            ->setTitle('OG Title')
            ->setDescription('OG Description')
            ->setOgImage('https://example.com/og.jpg')
            ->setOgType('article');

        $output = $this->renderMeta();

        $this->assertStringContainsString('<meta property="og:title" content="OG Title">', $output);
        $this->assertStringContainsString('<meta property="og:description" content="OG Description">', $output);
        $this->assertStringContainsString('<meta property="og:image" content="https://example.com/og.jpg">', $output);
        $this->assertStringContainsString('<meta property="og:type" content="article">', $output);
        $this->assertStringContainsString('<meta property="og:site_name" content="Test App">', $output);
        $this->assertStringContainsString('<meta property="og:locale" content="en_US">', $output);
    }

    public function test_component_renders_twitter_tags(): void
    {
        config()->set('laravel-seo.twitter_handle', '@testhandle');

        app(SeoService::class)
            ->setTitle('Twitter Title')
            ->setDescription('Twitter Description')
            ->setOgImage('https://example.com/og.jpg');

        $output = $this->renderMeta();

        $this->assertStringContainsString('<meta name="twitter:card" content="summary_large_image">', $output);
        $this->assertStringContainsString('<meta name="twitter:site" content="@testhandle">', $output);
        $this->assertStringContainsString('<meta name="twitter:title" content="Twitter Title">', $output);
        $this->assertStringContainsString('<meta name="twitter:description" content="Twitter Description">', $output);
        $this->assertStringContainsString('<meta name="twitter:image" content="https://example.com/og.jpg">', $output);
    }

    public function test_component_renders_json_ld(): void
    {
        app(SeoService::class)->addJsonLd([
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => 'Test Page',
        ]);

        $output = $this->renderMeta();

        $this->assertStringContainsString('application/ld+json', $output);
        $this->assertStringContainsString('"@type": "WebPage"', $output);
        $this->assertStringContainsString('"name": "Test Page"', $output);
    }

    public function test_component_renders_noindex_when_set(): void
    {
        app(SeoService::class)->setNoindex(true);

        $output = $this->renderMeta();

        $this->assertStringContainsString('<meta name="robots" content="noindex, nofollow">', $output);
        $this->assertStringNotContainsString('content="index, follow"', $output);
    }
}
