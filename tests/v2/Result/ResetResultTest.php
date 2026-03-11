<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result;

use GridCP\Proxmox\Result\ResetResult;
use GridCP\Proxmox\Result\ResultConverter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResetResultTest extends TestCase
{
    public function testNormalizeCurrentResponseToDto(): void
    {
        $converter = new ResultConverter();

        $json = json_encode([
            'data' => 'UPID:ns1047:0038862F:3D66BAFA:68D2D4BA:qmpause:101:root@pam:',
        ], JSON_THROW_ON_ERROR);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $result = $converter->convert($response, ResetResult::class);

        $this->assertInstanceOf(ResetResult::class, $result);
        $this->assertSame('UPID:ns1047:0038862F:3D66BAFA:68D2D4BA:qmpause:101:root@pam:', $result->upid);
    }

    public function testHandlesVmNotRunningError(): void
    {
        $converter = new ResultConverter();

        $json = json_encode([
            'data' => null,
            'message' => "VM 101 not running\n",
        ], JSON_THROW_ON_ERROR);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response->method('getStatusCode')->willReturn(500);
        $response->method('getBody')->willReturn($stream);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("VM 101 not running\n");

        $converter->convert($response, ResetResult::class);
    }
}
