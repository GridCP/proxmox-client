<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result;

use GridCP\Proxmox\Result\ResultConverter;
use GridCP\Proxmox\Result\ShutdownResult;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ShutdownResultTest extends TestCase
{
    public function testOnOkResponse(): void
    {
        $converter = new ResultConverter();

        $json = json_encode([
            'data' => 'UPID:ns2202:000EC7C7:007F6FDB:69B2BD45:qmshutdown:100:root@pam!gridcp:',
        ]);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getReasonPhrase')->willReturn('OK');
        $response->method('getBody')->willReturn($stream);

        $result = $converter->convert($response, ShutdownResult::class);

        $this->assertSame(
            'UPID:ns2202:000EC7C7:007F6FDB:69B2BD45:qmshutdown:100:root@pam!gridcp:',
            $result->upid,
        );
    }
}
