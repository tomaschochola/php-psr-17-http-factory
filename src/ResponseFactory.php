<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Http\Factory;

use NoDiscard;
use Override;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use TomasChochola\Psr\Http\Message\HttpHeaders;
use TomasChochola\Psr\Http\Message\HttpResponse;

/**
 * @no-named-arguments
 */
readonly class ResponseFactory implements ResponseFactoryInterface
{
    private StreamFactoryInterface $streamFactory;

    public function __construct(StreamFactoryInterface $streamFactory)
    {
        $this->streamFactory = $streamFactory;
    }

    #[NoDiscard()]
    #[Override()]
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new HttpResponse($this->streamFactory->createStream(), new HttpHeaders([]), '', $code, $reasonPhrase);
    }
}
