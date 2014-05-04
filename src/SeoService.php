<?php

declare(strict_types=1);

namespace PhilipRehberger\Seo;

use Illuminate\Support\Facades\URL;

class SeoService
{
    private ?string $title = null;

    private ?string $description = null;

    private ?string $canonical = null;

    private ?string $ogImage = null;

    private ?string $ogType = null;

    private bool $noindex = false;

    private array $jsonLd = [];

    /**
     * Set the page title.
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the meta description.
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the canonical URL.
     */
    public function setCanonical(?string $url): self
    {
        $this->canonical = $url;

        return $this;
    }

    /**
     * Set the Open Graph image.
     */
    public function setOgImage(?string $image): self
    {
        $this->ogImage = $image;

        return $this;
    }

    /**
     * Set the Open Graph type.
     */
    public function setOgType(?string $type): self
    {
        $this->ogType = $type;

        return $this;
    }

    /**
     * Set noindex flag.
     */
    public function setNoindex(bool $noindex = true): self
    {
        $this->noindex = $noindex;

        return $this;
    }

    /**
     * Add JSON-LD structured data.
     */
    public function addJsonLd(array $schema): self
    {
        $this->jsonLd[] = $schema;

        return $this;
    }

    /**
     * Get the page title.
     */
    public function getTitle(): string
    {
        return $this->title ?? config('laravel-seo.default_title') ?? '';
    }

    /**
     * Get the meta description.
     */
    public function getDescription(): string
    {
        return $this->description ?? config('laravel-seo.default_description') ?? '';
    }

    /**
     * Get the canonical URL.
     */
    public function getCanonical(): string
    {
        return $this->canonical ?? URL::current();
    }

    /**
     * Get the Open Graph image URL.
     */
    public function getOgImage(): string
    {
        $image = $this->ogImage ?? config('laravel-seo.og_image');

        // Convert relative path to absolute URL
        if ($image && ! str_starts_with($image, 'http')) {
            return url($image);
        }

        return $image ?? '';
    }

    /**
     * Get the Open Graph type.
     */
    public function getOgType(): string
    {
        return $this->ogType ?? config('laravel-seo.og_type') ?? 'website';
    }

    /**
     * Check if page should be noindexed.
     */
    public function isNoindex(): bool
    {
        return $this->noindex;
    }

    /**
     * Get all JSON-LD schemas.
     */
    public function getJsonLd(): array
    {
        return $this->jsonLd;
    }

    /**
     * Get the site name.
     */
    public function getSiteName(): string
    {
        return config('laravel-seo.site_name') ?? config('app.name') ?? '';
    }

    /**
     * Get the locale.
     */
    public function getLocale(): string
    {
        return config('laravel-seo.locale') ?? 'en_US';
    }

    /**
     * Get the Twitter handle.
     */
    public function getTwitterHandle(): string
    {
        return config('laravel-seo.twitter_handle') ?? '';
    }

    /**
     * Load SEO data for a specific page from config.
     */
    public function forPage(string $pageKey): self
    {
        $pageData = config("laravel-seo.pages.{$pageKey}", []);

        if (isset($pageData['title'])) {
            $this->setTitle($pageData['title']);
        }

        if (isset($pageData['description'])) {
            $this->setDescription($pageData['description']);
        }

        return $this;
    }

    /**
     * Generate Organization JSON-LD schema.
     */
    public function getOrganizationSchema(): array
    {
        $org = config('laravel-seo.organization', []);

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $org['name'] ?? $this->getSiteName(),
            'url' => $org['url'] ?? url('/'),
        ];

        if (! empty($org['logo'])) {
            $schema['logo'] = url($org['logo']);
        }

        if (! empty($org['email'])) {
            $schema['contactPoint'] = [
                '@type' => 'ContactPoint',
                'email' => $org['email'],
                'contactType' => 'customer service',
            ];
        }

        if (! empty($org['description'])) {
            $schema['description'] = $org['description'];
        }

        if (! empty($org['founding_date'])) {
            $schema['foundingDate'] = $org['founding_date'];
        }

        if (! empty($org['same_as'])) {
            $schema['sameAs'] = $org['same_as'];
        }

        return $schema;
    }

    /**
     * Generate WebSite JSON-LD schema.
     */
    public function getWebsiteSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $this->getSiteName(),
            'url' => url('/'),
        ];
    }

    /**
     * Generate Service JSON-LD schema.
     */
    public function getServiceSchema(string $name, string $description, ?string $serviceType = null): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $name,
            'description' => $description,
            'provider' => [
                '@type' => 'Organization',
                'name' => $this->getSiteName(),
            ],
            'areaServed' => 'Worldwide',
        ];

        if ($serviceType) {
            $schema['serviceType'] = $serviceType;
        }

        return $schema;
    }

    /**
     * Generate BreadcrumbList JSON-LD schema.
     */
    public function getBreadcrumbSchema(array $items): array
    {
        $listItems = [];
        foreach ($items as $index => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ];
    }

    /**
     * Reset all values (useful when reusing across multiple requests).
     */
    public function reset(): self
    {
        $this->title = null;
        $this->description = null;
        $this->canonical = null;
        $this->ogImage = null;
        $this->ogType = null;
        $this->noindex = false;
        $this->jsonLd = [];

        return $this;
    }
}
