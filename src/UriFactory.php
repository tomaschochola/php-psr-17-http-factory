<?php

/**
 * @author Tomáš Chochola <tomaschochola@tomaschochola.cz>
 * @copyright © 2026 Tomáš Chochola <tomaschochola@tomaschochola.cz>
 *
 * @license CC-BY-ND-4.0
 *
 * @see {@link https://creativecommons.org/licenses/by-nd/4.0/} License
 * @see {@link https://github.com/tomaschochola} GitHub Profile
 * @see {@link https://github.com/sponsors/tomaschochola} GitHub Sponsors
 */

declare(strict_types=1);

namespace TomasChochola\Psr\Http\Factory;

use NoDiscard;
use Override;
use Psr\Container\ContainerInterface;
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
    #[NoDiscard]
    public static function inject(ContainerInterface $container): self
    {
        return new self();
    }

    #[NoDiscard]
    #[Override]
    public function createUri(string $uri = ''): UriInterface
    {
        $parsed = Uri::parse($uri);

        if ($parsed !== null) {
            return new HttpUri($parsed);
        }

        return new HttpUri(new Url($uri));
    }
}
