<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result\Qemu;

use GridCP\Proxmox\Result\Qemu\ExecStatusResult;
use GridCP\Proxmox\Result\ResultConverter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ExecStatusResultTest extends TestCase
{
    public function testNormalizeResponseToDto(): void
    {
        $json = json_encode([
            'data' => [
                'exited' => 0,
                'exitcode' => 0,
                'out-data' => 'command output',
                'out-truncated' => 0,
                'err-data' => null,
                'err-truncated' => 0,
                'signal' => null,
            ],
        ], JSON_THROW_ON_ERROR);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $converter = new ResultConverter();
        $result = $converter->convert($response, ExecStatusResult::class);

        $this->assertInstanceOf(ExecStatusResult::class, $result);
        $this->assertSame('command output', $result->outData);
        $this->assertTrue($result->isSuccess());
        $this->assertFalse($result->isError());
    }
}
