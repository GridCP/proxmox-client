<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result\Qemu;

use GridCP\Proxmox\Result\Qemu\ExecResult;
use GridCP\Proxmox\Result\ResultConverter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ExecResultTest extends TestCase
{
    public function testNormalizeResponseToDto(): void
    {
        $json = json_encode([
            'data' => [
                'pid' => 12345,
            ],
        ], JSON_THROW_ON_ERROR);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $converter = new ResultConverter();
        $result = $converter->convert($response, ExecResult::class);

        $this->assertInstanceOf(ExecResult::class, $result);
        $this->assertSame(12345, $result->pid);
    }
}
