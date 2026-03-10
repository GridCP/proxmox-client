<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result;

use GridCP\Proxmox\Result\ResultConverter;
use GridCP\Proxmox\Result\StatusResult;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CurrentResultTest extends TestCase
{
    public function testNormalizeCurrentResponseToDto(): void
    {
        $converter = new ResultConverter();

        $json = json_encode([
            'data' => [
                'subdir' => '/foo/bar/foo',
            ],
        ], JSON_THROW_ON_ERROR);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $result = $converter->convert($response, StatusResult::class);

        $this->assertInstanceOf(StatusResult::class, $result);
        $this->assertSame('/foo/bar/foo', $result->subdir);
    }
}
