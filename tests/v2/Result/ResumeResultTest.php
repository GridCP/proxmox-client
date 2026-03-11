<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result;

use GridCP\Proxmox\Result\ResultConverter;
use GridCP\Proxmox\Result\ResumeResult;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResumeResultTest extends TestCase
{
    public function testNormalizeCurrentResponseToDto(): void
    {
        $converter = new ResultConverter();

        $json = json_encode([
            'data' => 'UPID:ns2202:00018774:000C6D15:69B196D8:qmresume:100:root@pam!gridcp:',
        ], JSON_THROW_ON_ERROR);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $result = $converter->convert($response, ResumeResult::class);

        $this->assertInstanceOf(ResumeResult::class, $result);
        $this->assertSame(
            'UPID:ns2202:00018774:000C6D15:69B196D8:qmresume:100:root@pam!gridcp:',
            $result->upid,
        );
    }
}
