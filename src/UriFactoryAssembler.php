<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Http\Factory;

use NoDiscard;
use Psr\Container\ContainerInterface;

/**
 * @no-named-arguments
 */
readonly class UriFactoryAssembler
{
    #[NoDiscard]
    public static function assemble(ContainerInterface $container): UriFactory
    {
        return new UriFactory();
    }
}
