# Changelog

All notable changes to `laravel-seo` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.1.1] - 2026-03-31

### Changed
- Standardize README to 3-badge format with emoji Support section
- Update CI checkout action to v5 for Node.js 24 compatibility
- Add GitHub issue templates, dependabot config, and PR template

## [1.1.0] - 2026-03-22

### Added
- `OgType` backed enum for type-safe Open Graph type setting
- `setOgType()` method accepting both `OgType` enum and string values
- `setOgImageAlt()` method for Open Graph image alt text

## [1.0.4] - 2026-03-17

### Fixed
- Add phpstan.neon configuration for CI static analysis

## [1.0.3] - 2026-03-17

### Changed
- Standardized package metadata, README structure, and CI workflow per package guide

## [1.0.2] - 2026-03-16

### Changed
- Standardize composer.json: add type, homepage, scripts

## [1.0.1] - 2026-03-15

### Changed
- Add README badges

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
