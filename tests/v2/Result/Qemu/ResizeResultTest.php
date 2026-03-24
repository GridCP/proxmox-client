<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result\Qemu;

use GridCP\Proxmox\Result\Qemu\ResizeResult;
use GridCP\Proxmox\Result\ResultConverter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResizeResultTest extends TestCase
{
    public function testNormalizeResponseToDto(): void
    {
        $converter = new ResultConverter();

        $json = json_encode([
            'data' => 'UPID:nodeName:XXXXXXXX:XXXXXXXX:XXXXXXXX:resize:101:root@pam!gridcp:',
        ], JSON_THROW_ON_ERROR);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $result = $converter->convert($response, ResizeResult::class);

        $this->assertInstanceOf(ResizeResult::class, $result);
        $this->assertSame(
            'UPID:nodeName:XXXXXXXX:XXXXXXXX:XXXXXXXX:resize:101:root@pam!gridcp:',
            $result->upid,
        );
    }
}
