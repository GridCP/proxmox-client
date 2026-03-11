<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result;

use GridCP\Proxmox\Result\ResultConverter;
use GridCP\Proxmox\Result\StartResult;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class StartResultTest extends TestCase
{
    public function testNormalizeCurrentResponseToDto(): void
    {
        $converter = new ResultConverter();

        $json = json_encode([
            'data' => 'UPID:ns2202:00016EB3:000B9BC2:69B194C0:qmstart:100:root@pam!gridcp:',
        ], JSON_THROW_ON_ERROR);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $result = $converter->convert($response, StartResult::class);

        $this->assertInstanceOf(StartResult::class, $result);
        $this->assertSame(
            'UPID:ns2202:00016EB3:000B9BC2:69B194C0:qmstart:100:root@pam!gridcp:',
            $result->upid,
        );
    }
}
