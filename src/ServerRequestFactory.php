<?php

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

use function is_string;

/**
 * @no-named-arguments
 */
readonly class ServerRequestFactory implements ServerRequestFactoryInterface
{
    private StreamFactoryInterface $streamFactory;

    private UriFactoryInterface $uriFactory;

    public function __construct(StreamFactoryInterface $streamFactory, UriFactoryInterface $uriFactory)
    {
        $this->streamFactory = $streamFactory;
        $this->uriFactory = $uriFactory;
    }

    /**
     * @param array<mixed, mixed> $serverParams
     */
    #[NoDiscard()]
    #[Override()]
    public function createServerRequest(string $method, mixed $uri, array $serverParams = []): ServerRequestInterface
    {
        return new HttpServerRequest($this->streamFactory->createStream(), new HttpHeaders([]), '', $method, is_string($uri) ? $this->uriFactory->createUri($uri) : $uri, '', $serverParams, [], [], [], null, []);
    }
}
