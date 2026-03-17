<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result;

use GridCP\Proxmox\Result\DestroyResult;
use GridCP\Proxmox\Result\ResultConverter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class DestroyResultTest extends TestCase
{
    public function testNormalizeCurrentResponseToDto(): void
    {
        $converter = new ResultConverter();

        $json = json_encode([
            'data' => 'UPID:ns2202:001CCD1D:031F6F63:69B97596:qmdestroy:100:root@pam!gridcp:',
        ], JSON_THROW_ON_ERROR);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $result = $converter->convert($response, DestroyResult::class);

        $this->assertInstanceOf(DestroyResult::class, $result);
        $this->assertSame(
            'UPID:ns2202:001CCD1D:031F6F63:69B97596:qmdestroy:100:root@pam!gridcp:',
            $result->upid,
        );
    }
}
