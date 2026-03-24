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
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use TomasChochola\Psr\Http\Message\HttpHeaders;
use TomasChochola\Psr\Http\Message\HttpServerRequest;

use function assert;
use function is_string;

/**
 * @no-named-arguments
 */
readonly class ServerRequestFactory implements ServerRequestFactoryInterface
{
    private readonly StreamFactoryInterface $streamFactory;

    private readonly UriFactoryInterface $uriFactory;

    public function __construct(StreamFactoryInterface $streamFactory, UriFactoryInterface $uriFactory)
    {
        $this->streamFactory = $streamFactory;
        $this->uriFactory = $uriFactory;
    }

    /**
     * @param array<mixed, mixed> $serverParams
     */
    #[NoDiscard]
    #[Override]
    public function createServerRequest(string $method, mixed $uri, array $serverParams = []): ServerRequestInterface
    {
        return new HttpServerRequest($this->streamFactory->createStream(), new HttpHeaders([]), '', $method, is_string($uri) ? $this->uriFactory->createUri($uri) : $uri, '', $serverParams, [], [], [], null, []);
    }
}
