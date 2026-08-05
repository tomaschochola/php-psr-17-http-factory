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

namespace Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use TomasChochola\Psr\Http\Factory\CgiServerRequestFactory;
use TomasChochola\Psr\Http\Factory\RequestFactory;
use TomasChochola\Psr\Http\Factory\ResponseFactory;
use TomasChochola\Psr\Http\Factory\ServerRequestFactory;
use TomasChochola\Psr\Http\Factory\StreamFactory;
use TomasChochola\Psr\Http\Factory\UploadedFileFactory;
use TomasChochola\Psr\Http\Factory\UriFactory;
use TomasChochola\Psr\Http\Message\HttpRequest;
use TomasChochola\Psr\Http\Message\HttpResponse;
use TomasChochola\Psr\Http\Message\HttpServerRequest;
use TomasChochola\Psr\Http\Message\HttpStream;
use TomasChochola\Psr\Http\Message\HttpUploadedFile;
use TomasChochola\Psr\Http\Message\HttpUri;
use UnexpectedValueException;

use function file_put_contents;
use function fopen;
use function sys_get_temp_dir;
use function tempnam;
use function unlink;

use const UPLOAD_ERR_OK;

/**
 * @internal
 *
 * @no-named-arguments
 */
#[CoversClass(CgiServerRequestFactory::class)]
#[CoversClass(RequestFactory::class)]
#[CoversClass(ResponseFactory::class)]
#[CoversClass(ServerRequestFactory::class)]
#[CoversClass(StreamFactory::class)]
#[CoversClass(UploadedFileFactory::class)]
#[CoversClass(UriFactory::class)]
#[Small()]
final class HttpFactoryTest extends TestCase
{
    #[Test()]
    public function cgiFactoryRejectsMissingRequestMetadata(): void
    {
        $streamFactory = new StreamFactory();
        $factory = new CgiServerRequestFactory(new ServerRequestFactory($streamFactory, new UriFactory()));

        $this->expectException(UnexpectedValueException::class);

        (void) $factory->create();
    }

    #[Test()]
    public function createsRequestsResponsesAndServerRequests(): void
    {
        $streamFactory = new StreamFactory();
        $uriFactory = new UriFactory();
        $uri = $uriFactory->createUri('https://example.com/resource');
        $requestFactory = new RequestFactory($streamFactory, $uriFactory);
        $request = $requestFactory->createRequest('GET', '/relative');
        $requestFromUri = $requestFactory->createRequest('POST', $uri);

        self::assertInstanceOf(HttpRequest::class, $request);
        self::assertSame('GET', $request->getMethod());
        self::assertSame('/relative', (string) $request->getUri());
        self::assertSame('', (string) $request->getBody());
        self::assertSame($uri, $requestFromUri->getUri());

        $response = (new ResponseFactory($streamFactory))->createResponse(201, 'Created');

        self::assertInstanceOf(HttpResponse::class, $response);
        self::assertSame(201, $response->getStatusCode());
        self::assertSame('Created', $response->getReasonPhrase());
        self::assertSame('', (string) $response->getBody());

        $serverRequestFactory = new ServerRequestFactory($streamFactory, $uriFactory);
        $serverRequest = $serverRequestFactory->createServerRequest('PUT', '/resource', ['REMOTE_ADDR' => '127.0.0.1']);
        $serverRequestFromUri = $serverRequestFactory->createServerRequest('PATCH', $uri);

        self::assertInstanceOf(HttpServerRequest::class, $serverRequest);
        self::assertSame('PUT', $serverRequest->getMethod());
        self::assertSame('/resource', (string) $serverRequest->getUri());
        self::assertSame(['REMOTE_ADDR' => '127.0.0.1'], $serverRequest->getServerParams());
        self::assertSame($uri, $serverRequestFromUri->getUri());
    }

    #[Test()]
    public function createsRfc3986AndWhatWgUris(): void
    {
        $factory = new UriFactory();
        $relative = $factory->createUri('/path?query=value');
        $url = $factory->createUri('https://example.com/space here');

        self::assertInstanceOf(HttpUri::class, $relative);
        self::assertSame('/path', $relative->getPath());
        self::assertSame('query=value', $relative->getQuery());
        self::assertInstanceOf(HttpUri::class, $url);
        self::assertSame('https://example.com/space%20here', (string) $url);
    }

    #[Test()]
    public function createsStreamsFromContentFilesAndResources(): void
    {
        $factory = new StreamFactory();
        $stream = $factory->createStream('žluťoučký');

        self::assertInstanceOf(HttpStream::class, $stream);
        self::assertSame('žluťoučký', (string) $stream);

        $resource = fopen('php://temp', 'w+b');
        self::assertIsResource($resource);
        self::assertInstanceOf(HttpStream::class, $factory->createStreamFromResource($resource));

        $file = tempnam(sys_get_temp_dir(), 'http-factory-');
        self::assertIsString($file);
        self::assertSame(7, file_put_contents($file, 'content'));

        $fileStream = $factory->createStreamFromFile($file);

        self::assertSame('content', (string) $fileStream);
        self::assertTrue(unlink($file));
    }

    #[Test()]
    public function createsUploadedFilesWithProvidedMetadata(): void
    {
        $stream = (new StreamFactory())->createStream('payload');
        $upload = (new UploadedFileFactory())->createUploadedFile($stream, 7, UPLOAD_ERR_OK, 'payload.txt', 'text/plain');

        self::assertInstanceOf(HttpUploadedFile::class, $upload);
        self::assertSame($stream, $upload->getStream());
        self::assertSame(7, $upload->getSize());
        self::assertSame(UPLOAD_ERR_OK, $upload->getError());
        self::assertSame('payload.txt', $upload->getClientFilename());
        self::assertSame('text/plain', $upload->getClientMediaType());
    }
}
