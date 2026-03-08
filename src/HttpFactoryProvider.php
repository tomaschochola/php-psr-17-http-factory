<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Http\Factory;

use IteratorAggregate;
use NoDiscard;
use Override;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Traversable;

/**
 * @no-named-arguments
 *
 * @implements IteratorAggregate<mixed, mixed>
 */
readonly class HttpFactoryProvider implements IteratorAggregate
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield RequestFactory::class => [RequestFactory::class, 'unload'];
        yield RequestFactoryInterface::class => [RequestFactory::class, 'unload'];
        yield ResponseFactory::class => [ResponseFactory::class, 'unload'];
        yield ResponseFactoryInterface::class => [ResponseFactory::class, 'unload'];
        yield ServerRequestFactory::class => [ServerRequestFactory::class, 'unload'];
        yield ServerRequestFactoryInterface::class => [ServerRequestFactory::class, 'unload'];
        yield ServerRequestInterface::class => [ServerRequestFactory::class, 'produce'];
        yield StreamFactory::class => [StreamFactory::class, 'unload'];
        yield StreamFactoryInterface::class => [StreamFactory::class, 'unload'];
        yield UriFactory::class => [UriFactory::class, 'unload'];
        yield UriFactoryInterface::class => [UriFactory::class, 'unload'];
    }
}
