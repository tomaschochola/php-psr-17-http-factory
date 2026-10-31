<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Http\Factory;

use NoDiscard;
use Override;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use TomasChochola\Psr\Http\Message\HttpUri;
use Uri\Rfc3986\Uri;
use Uri\WhatWg\Url;

/**
 * @no-named-arguments
 */
readonly class UriFactory implements UriFactoryInterface
{
    #[NoDiscard()]
    #[Override()]
    public function createUri(string $uri = ''): UriInterface
    {
        $parsed = Uri::parse($uri);

        if ($parsed !== null) {
            return new HttpUri($parsed);
        }

        return new HttpUri(new Url($uri));
    }
}
