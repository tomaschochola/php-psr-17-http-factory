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
readonly class HttpFactoryManifest implements IteratorAggregate
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield RequestFactory::class => [RequestFactoryAssembler::class, 'assemble'];
        yield RequestFactoryInterface::class => [RequestFactoryAssembler::class, 'assemble'];
        yield ResponseFactory::class => [ResponseFactoryAssembler::class, 'assemble'];
        yield ResponseFactoryInterface::class => [ResponseFactoryAssembler::class, 'assemble'];
        yield ServerRequestFactory::class => [ServerRequestFactoryAssembler::class, 'assemble'];
        yield ServerRequestFactoryInterface::class => [ServerRequestFactoryAssembler::class, 'assemble'];
        yield ServerRequestInterface::class => [ServerRequestFactory::class, 'produce'];
        yield StreamFactory::class => [StreamFactoryAssembler::class, 'assemble'];
        yield StreamFactoryInterface::class => [StreamFactoryAssembler::class, 'assemble'];
        yield UriFactory::class => [UriFactoryAssembler::class, 'assemble'];
        yield UriFactoryInterface::class => [UriFactoryAssembler::class, 'assemble'];
    }
}
