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
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

use function assert;
use function is_string;

/**
 * @no-named-arguments
 */
readonly class CgiServerRequestFactory
{
    private readonly ServerRequestFactoryInterface $factory;

    public function __construct(ServerRequestFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    #[NoDiscard]
    public static function inject(ContainerInterface $container): self
    {
        $factory = $container->get(ServerRequestFactoryInterface::class);

        assert($factory instanceof ServerRequestFactoryInterface);

        return new self($factory);
    }

    #[NoDiscard]
    public function create(): ServerRequestInterface
    {
        assert(isset($_SERVER['REQUEST_METHOD']) && is_string($_SERVER['REQUEST_METHOD']));
        assert(isset($_SERVER['REQUEST_URI']) && is_string($_SERVER['REQUEST_URI']));

        return $this->factory->createServerRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_SERVER);
    }

    #[NoDiscard]
    public static function produce(ContainerInterface $container): ServerRequestInterface
    {
        $factory = $container->get(static::class);

        assert($factory instanceof static);

        return $factory->create();
    }
}
