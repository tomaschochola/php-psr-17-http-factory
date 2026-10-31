<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Http\Factory;

use NoDiscard;
use Override;
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
    #[NoDiscard()]
    #[Override()]
    public function createUploadedFile(StreamInterface $stream, int | null $size = null, int $error = UPLOAD_ERR_OK, string | null $clientFilename = null, string | null $clientMediaType = null): UploadedFileInterface
    {
        return new HttpUploadedFile($stream, $size, $error, $clientFilename, $clientMediaType);
    }
}
