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
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use TomasChochola\Psr\Http\Message\HttpUri;
use UnexpectedValueException;
use Uri\WhatWg\Url;

use function filter_input_array;
use function is_array;
use function is_string;

use const INPUT_SERVER;

/**
 * @no-named-arguments
 */
readonly class CgiServerRequestFactory
{
    private ServerRequestFactoryInterface $factory;

    public function __construct(ServerRequestFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    #[NoDiscard()]
    public function create(): ServerRequestInterface
    {
        $server = filter_input_array(INPUT_SERVER);

        if (!is_array($server)) {
            throw new UnexpectedValueException('filter_input_array');
        }

        if (!isset($server['REQUEST_METHOD']) || !is_string($server['REQUEST_METHOD'])) {
            throw new UnexpectedValueException('$server');
        }

        if (!isset($server['REQUEST_URI']) || !is_string($server['REQUEST_URI'])) {
            throw new UnexpectedValueException('$server');
        }

        if (!isset($server['REQUEST_HOST']) || !is_string($server['REQUEST_HOST'])) {
            throw new UnexpectedValueException('$server');
        }

        if (!isset($server['REQUEST_SCHEME']) || !is_string($server['REQUEST_SCHEME'])) {
            throw new UnexpectedValueException('$server');
        }

        return $this->factory->createServerRequest($server['REQUEST_METHOD'], new HttpUri(new Url($server['REQUEST_SCHEME'] . '://' . $server['REQUEST_HOST'] . $server['REQUEST_URI'])), $server);
    }
}
