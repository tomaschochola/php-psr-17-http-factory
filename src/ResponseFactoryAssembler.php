<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Http\Factory;

use NoDiscard;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\StreamFactoryInterface;

use function assert;

/**
 * @no-named-arguments
 */
readonly class ResponseFactoryAssembler
{
    #[NoDiscard]
    public static function assemble(ContainerInterface $container): ResponseFactory
    {
        $streamFactory = $container->get(StreamFactoryInterface::class);

        assert($streamFactory instanceof StreamFactoryInterface);

        return new ResponseFactory($streamFactory);
    }
}
