<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Http\Factory;

use NoDiscard;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

use function assert;

/**
 * @no-named-arguments
 */
readonly class ServerRequestFactoryAssembler
{
    #[NoDiscard]
    public static function assemble(ContainerInterface $container): ServerRequestFactory
    {
        $streamFactory = $container->get(StreamFactoryInterface::class);
        $uriFactory = $container->get(UriFactoryInterface::class);

        assert($streamFactory instanceof StreamFactoryInterface);
        assert($uriFactory instanceof UriFactoryInterface);

        return new ServerRequestFactory($streamFactory, $uriFactory);
    }
}
