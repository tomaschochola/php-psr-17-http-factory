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
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use TomasChochola\Psr\Container\ProviderInterface;

use function assert;
use function is_string;

/**
 * @no-named-arguments
 */
readonly class ServerRequestProvider implements ProviderInterface
{
    #[Override]
    public static function provide(ContainerInterface $container): ServerRequestInterface
    {
        $serverRequestFactory = $container->get(ServerRequestFactoryInterface::class);

        assert($serverRequestFactory instanceof ServerRequestFactoryInterface);
        assert(isset($_SERVER['REQUEST_METHOD']) && is_string($_SERVER['REQUEST_METHOD']));
        assert(isset($_SERVER['REQUEST_URI']) && is_string($_SERVER['REQUEST_URI']));

        return $serverRequestFactory->createServerRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_SERVER);
    }
}
