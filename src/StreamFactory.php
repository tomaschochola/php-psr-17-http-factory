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
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use TomasChochola\Psr\Http\Message\Stream;
use UnexpectedValueException;

use function fopen;
use function is_resource;

/**
 * @no-named-arguments
 */
readonly class StreamFactory implements StreamFactoryInterface
{
    #[Override]
    public function createStream(string $content = ''): StreamInterface
    {
        $stream = $this->createStreamFromFile('php://temp/maxmemory:2097152', 'wb+');

        if ($content !== '') {
            $stream->write($content);
        }

        return $stream;
    }

    #[Override]
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        $handle = fopen($filename, $mode);

        if (!is_resource($handle)) {
            throw new UnexpectedValueException('fopen');
        }

        return $this->createStreamFromResource($handle);
    }

    #[Override]
    public function createStreamFromResource(mixed $resource): StreamInterface
    {
        return new Stream($resource);
    }
}
