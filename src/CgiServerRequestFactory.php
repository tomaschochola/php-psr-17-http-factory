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
use TomasChochola\Psr\Http\Message\HttpUri;
use Uri\WhatWg\Url;

use function assert;
use function filter_input_array;
use function is_array;
use function is_string;

use const INPUT_SERVER;

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
    public static function produce(ContainerInterface $container): ServerRequestInterface
    {
        $factory = $container->get(static::class);

        assert($factory instanceof static);

        $server = filter_input_array(INPUT_SERVER);

        assert(is_array($server));

        return $factory->create($server);
    }

    /**
     * @param array<mixed, mixed> $server
     */
    #[NoDiscard]
    public function create(array $server): ServerRequestInterface
    {
        assert(isset($server['REQUEST_METHOD']) && is_string($server['REQUEST_METHOD']));
        assert(isset($server['REQUEST_URI']) && is_string($server['REQUEST_URI']));
        assert(isset($server['REQUEST_HOST']) && is_string($server['REQUEST_HOST']));
        assert(isset($server['REQUEST_SCHEME']) && is_string($server['REQUEST_SCHEME']));

        return $this->factory->createServerRequest($server['REQUEST_METHOD'], new HttpUri(new Url($server['REQUEST_SCHEME'] . '://' . $server['REQUEST_HOST'] . $server['REQUEST_URI'])), $server);
    }
}
