# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2025-03-05

### Added
- Initial release
- `SeoService` with fluent API for setting title, description, canonical URL, OG image, OG type, and noindex flag
- JSON-LD structured data support with `addJsonLd()` and built-in schema generators
- Organization, WebSite, Service, and BreadcrumbList JSON-LD schema generators
- Page-specific SEO configuration via `laravel-seo.pages` config key
- `Seo` facade for convenient access
- Blade component `<x-seo::meta />` rendering all meta tags, OG tags, Twitter Card tags, and JSON-LD
- CSP nonce support on JSON-LD script tags (optional, works without the nonce)
- Publishable config and views
