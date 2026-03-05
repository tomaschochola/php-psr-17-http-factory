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
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use TomasChochola\Psr\Http\Message\Headers;
use TomasChochola\Psr\Http\Message\ServerRequest;

use function assert;
use function is_string;

/**
 * @no-named-arguments
 */
readonly class ServerRequestFactory implements ServerRequestFactoryInterface
{
    protected readonly StreamFactoryInterface $streamFactory;

    protected readonly UriFactoryInterface $uriFactory;

    public function __construct(StreamFactoryInterface $streamFactory, UriFactoryInterface $uriFactory)
    {
        $this->streamFactory = $streamFactory;
        $this->uriFactory = $uriFactory;
    }

    public static function fromGlobals(ContainerInterface $container): ServerRequestInterface
    {
        $serverRequestFactory = $container->get(ServerRequestFactoryInterface::class);

        assert($serverRequestFactory instanceof ServerRequestFactoryInterface);
        assert(isset($_SERVER['REQUEST_METHOD']) && is_string($_SERVER['REQUEST_METHOD']));
        assert(isset($_SERVER['REQUEST_URI']) && is_string($_SERVER['REQUEST_URI']));

        return $serverRequestFactory->createServerRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_SERVER);
    }

    public static function provide(ContainerInterface $container): ServerRequestFactoryInterface
    {
        $streamFactory = $container->get(StreamFactoryInterface::class);
        $uriFactory = $container->get(UriFactoryInterface::class);

        assert($streamFactory instanceof StreamFactoryInterface);
        assert($uriFactory instanceof UriFactoryInterface);

        return new self($streamFactory, $uriFactory);
    }

    /**
     * @param array<int|string, mixed> $serverParams
     */
    #[Override]
    public function createServerRequest(string $method, mixed $uri, array $serverParams = []): ServerRequestInterface
    {
        return new ServerRequest($this->streamFactory->createStream(), new Headers([]), '', $method, is_string($uri) ? $this->uriFactory->createUri($uri) : $uri, '', $serverParams, [], [], [], null, []);
    }
}
