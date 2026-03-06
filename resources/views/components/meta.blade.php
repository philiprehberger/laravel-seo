@props([
    'title' => null,
    'description' => null,
    'canonical' => null,
    'ogImage' => null,
    'ogType' => null,
    'noindex' => false,
    'jsonLd' => [],
])

@php
    use PhilipRehberger\Seo\SeoService;

    $seo = app(SeoService::class);

    $pageTitle = $title ?? $seo->getTitle();
    $pageDescription = $description ?? $seo->getDescription();
    $pageCanonical = $canonical ?? $seo->getCanonical();
    $pageOgImage = $ogImage ?? $seo->getOgImage();
    $pageOgType = ($ogType !== null) ? $ogType : $seo->getOgType();
    $siteName = $seo->getSiteName();
    $locale = $seo->getLocale();
    $twitterHandle = $seo->getTwitterHandle();
    $shouldNoindex = $noindex || $seo->isNoindex();

    // Merge component JSON-LD with service JSON-LD
    $allJsonLd = array_merge($seo->getJsonLd(), $jsonLd);

    // Always include Organization schema
    if (empty($allJsonLd)) {
        $allJsonLd[] = $seo->getOrganizationSchema();
    }
@endphp

{{-- Primary Meta Tags --}}
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}">
@if($shouldNoindex)
<meta name="robots" content="noindex, nofollow">
@else
<meta name="robots" content="index, follow">
@endif

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $pageCanonical }}">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="{{ $pageOgType }}">
<meta property="og:url" content="{{ $pageCanonical }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="{{ $locale }}">
@if($pageOgImage)
<meta property="og:image" content="{{ $pageOgImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
@endif

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
@if($twitterHandle)
<meta name="twitter:site" content="{{ $twitterHandle }}">
<meta name="twitter:creator" content="{{ $twitterHandle }}">
@endif
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
@if($pageOgImage)
<meta name="twitter:image" content="{{ $pageOgImage }}">
@endif

{{-- JSON-LD Structured Data --}}
@foreach($allJsonLd as $schema)
@if(isset($cspNonce) && $cspNonce)
<script nonce="{{ $cspNonce }}" type="application/ld+json">
@else
<script type="application/ld+json">
@endif
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endforeach
