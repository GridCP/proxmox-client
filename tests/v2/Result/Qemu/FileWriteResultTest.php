<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result\Qemu;

use GridCP\Proxmox\Result\Qemu\FileWriteResult;
use GridCP\Proxmox\Result\ResultConverter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class FileWriteResultTest extends TestCase
{
    public function testNormalizeResponseToDto(): void
    {
        $json = json_encode([
            'data' => [
                'result' => null,
            ],
        ], JSON_THROW_ON_ERROR);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $converter = new ResultConverter();
        $result = $converter->convert($response, FileWriteResult::class);

        $this->assertInstanceOf(FileWriteResult::class, $result);
        $this->assertSame(null, $result->result);
    }

    public function testNormalizeResponseToDtoWithAgentError(): void
    {
        $json = json_encode([
            'message' => "Agent error: failed to open file 'g:\\diskpart.txt': The system cannot find the path specified.",
            'data' => null,
        ], JSON_THROW_ON_ERROR);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(500);
        $response->method('getBody')->willReturn($stream);

        $converter = new ResultConverter();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Agent error: failed to open file 'g:\\diskpart.txt': The system cannot find the path specified.");

        $converter->convert($response, FileWriteResult::class);
    }
}
