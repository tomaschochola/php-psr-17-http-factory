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
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use TomasChochola\Psr\Http\Message\HttpHeaders;
use TomasChochola\Psr\Http\Message\HttpRequest;

use function assert;

/**
 * @no-named-arguments
 */
readonly class RequestForge implements RequestFactoryInterface
{
    protected readonly StreamFactoryInterface $streamFactory;

    protected readonly UriFactoryInterface $uriFactory;

    public function __construct(StreamFactoryInterface $streamFactory, UriFactoryInterface $uriFactory)
    {
        $this->streamFactory = $streamFactory;
        $this->uriFactory = $uriFactory;
    }

    public static function unload(ContainerInterface $container): self
    {
        $streamFactory = $container->get(StreamFactoryInterface::class);
        $uriFactory = $container->get(UriFactoryInterface::class);

        assert($streamFactory instanceof StreamFactoryInterface);
        assert($uriFactory instanceof UriFactoryInterface);

        return new self($streamFactory, $uriFactory);
    }

    #[Override]
    public function createRequest(string $method, mixed $uri): RequestInterface
    {
        return new HttpRequest($this->streamFactory->createStream(), new HttpHeaders([]), '', $method, $uri instanceof UriInterface ? $uri : $this->uriFactory->createUri($uri), '');
    }
}
