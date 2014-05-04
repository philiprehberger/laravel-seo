<?php

declare(strict_types=1);

namespace PhilipRehberger\Seo\Facades;

use Illuminate\Support\Facades\Facade;
use PhilipRehberger\Seo\SeoService;

/**
 * @method static SeoService setTitle(?string $title)
 * @method static SeoService setDescription(?string $description)
 * @method static SeoService setCanonical(?string $url)
 * @method static SeoService setOgImage(?string $image)
 * @method static SeoService setOgType(?string $type)
 * @method static SeoService setNoindex(bool $noindex = true)
 * @method static SeoService addJsonLd(array $schema)
 * @method static string getTitle()
 * @method static string getDescription()
 * @method static string getCanonical()
 * @method static string getOgImage()
 * @method static string getOgType()
 * @method static bool isNoindex()
 * @method static array getJsonLd()
 * @method static string getSiteName()
 * @method static string getLocale()
 * @method static string getTwitterHandle()
 * @method static SeoService forPage(string $pageKey)
 * @method static array getOrganizationSchema()
 * @method static array getWebsiteSchema()
 * @method static array getServiceSchema(string $name, string $description, ?string $serviceType = null)
 * @method static array getBreadcrumbSchema(array $items)
 * @method static SeoService reset()
 *
 * @see \PhilipRehberger\Seo\SeoService
 */
class Seo extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return SeoService::class;
    }
}
