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
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use TomasChochola\Psr\Http\Message\HttpHeaders;
use TomasChochola\Psr\Http\Message\HttpResponse;

use function assert;

/**
 * @no-named-arguments
 */
readonly class ResponseFactory implements ResponseFactoryInterface
{
    protected readonly StreamFactoryInterface $streamFactory;

    public function __construct(StreamFactoryInterface $streamFactory)
    {
        $this->streamFactory = $streamFactory;
    }

    public static function unload(ContainerInterface $container): self
    {
        $streamFactory = $container->get(StreamFactoryInterface::class);

        assert($streamFactory instanceof StreamFactoryInterface);

        return new self($streamFactory);
    }

    #[Override]
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new HttpResponse($this->streamFactory->createStream(), new HttpHeaders([]), '', $code, $reasonPhrase);
    }
}
