<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result;

use GridCP\Proxmox\Result\ResultConverter;
use GridCP\Proxmox\Result\StopResult;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class StopResultTest extends TestCase
{
    public function testOnOkResponse(): void
    {
        $converter = new ResultConverter();

        $json = json_encode([
            'data' => 'UPID:ns2202:0010EE9E:00921A9C:69B2ED0F:qmstop:100:root@pam!gridcp:',
        ], JSON_THROW_ON_ERROR);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getReasonPhrase')->willReturn('OK');
        $response->method('getBody')->willReturn($stream);

        $result = $converter->convert($response, StopResult::class);

        $this->assertInstanceOf(StopResult::class, $result);
        $this->assertSame(
            'UPID:ns2202:0010EE9E:00921A9C:69B2ED0F:qmstop:100:root@pam!gridcp:',
            $result->upid,
        );
    }

    public function testOnVmNotRunningErrorResponse(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('VM 100 not running');

        $converter = new ResultConverter();

        $json = json_encode([
            'data' => null,
            'message' => 'VM 100 not running',
        ], JSON_THROW_ON_ERROR);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response->method('getStatusCode')->willReturn(500);
        $response->method('getReasonPhrase')->willReturn('VM 100 not running');
        $response->method('getBody')->willReturn($stream);

        $converter->convert($response, StopResult::class);
    }
}
