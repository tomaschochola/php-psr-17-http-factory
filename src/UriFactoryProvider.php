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

use Override;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\UriFactoryInterface;
use TomasChochola\Psr\Container\ProviderInterface;

/**
 * @no-named-arguments
 */
readonly class UriFactoryProvider implements ProviderInterface
{
    #[Override]
    public static function provide(ContainerInterface $container): UriFactoryInterface
    {
        return new UriFactory();
    }
}
