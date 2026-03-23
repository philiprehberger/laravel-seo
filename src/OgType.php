<?php

declare(strict_types=1);

namespace PhilipRehberger\Seo;

enum OgType: string
{
    case Website = 'website';
    case Article = 'article';
    case Product = 'product';
    case Profile = 'profile';
    case Video = 'video.other';
    case Music = 'music.song';
}
