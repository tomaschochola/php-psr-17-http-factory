<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Http\Factory;

use NoDiscard;
use Psr\Container\ContainerInterface;

/**
 * @no-named-arguments
 */
readonly class StreamFactoryAssembler
{
    #[NoDiscard]
    public static function assemble(ContainerInterface $container): StreamFactory
    {
        return new StreamFactory();
    }
}
