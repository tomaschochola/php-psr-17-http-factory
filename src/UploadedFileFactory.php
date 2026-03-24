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
use Override;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UploadedFileInterface;
use TomasChochola\Psr\Http\Message\HttpUploadedFile;

use const UPLOAD_ERR_OK;

/**
 * @no-named-arguments
 */
readonly class UploadedFileFactory implements UploadedFileFactoryInterface
{
    #[NoDiscard]
    public static function inject(ContainerInterface $container): self
    {
        return new self();
    }

    #[NoDiscard]
    #[Override]
    public function createUploadedFile(StreamInterface $stream, int|null $size = null, int $error = UPLOAD_ERR_OK, string|null $clientFilename = null, string|null $clientMediaType = null): UploadedFileInterface
    {
        return new HttpUploadedFile($stream, $size, $error, $clientFilename, $clientMediaType);
    }
}
