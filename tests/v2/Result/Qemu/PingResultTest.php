<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result\Qemu;

use GridCP\Proxmox\Result\Qemu\PingResult;
use GridCP\Proxmox\Result\ResultConverter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class PingResultTest extends TestCase
{
    public function testNormalizeResponseToDtoWithEmptyResultObject(): void
    {
        $json = json_encode([
            'data' => [
                'result' => [],
            ],
        ], JSON_THROW_ON_ERROR);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $converter = new ResultConverter();
        $result = $converter->convert($response, PingResult::class);

        $this->assertInstanceOf(PingResult::class, $result);
        $this->assertSame([], $result->result);
        $this->assertTrue($result->isOk());
    }
}
