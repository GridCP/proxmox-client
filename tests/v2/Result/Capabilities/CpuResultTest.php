<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result\Capabilities;

use GridCP\Proxmox\Result\Capabilities\CpuResult;
use GridCP\Proxmox\Result\ResultConverter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CpuResultTest extends TestCase
{
    public function testConvertCollectionAndCustomFlag(): void
    {
        $converter = new ResultConverter();
        $payload = json_encode([
            'data' => [
                [
                    'vendor' => 'GenuineIntel',
                    'name' => 'Skylake-Server-v4',
                    'custom' => false,
                ],
                [
                    'vendor' => 'default',
                    'name' => 'host',
                    'custom' => true,
                ],
            ],
        ], JSON_THROW_ON_ERROR);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($payload);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getReasonPhrase')->willReturn('OK');
        $response->method('getBody')->willReturn($stream);

        $result = $converter->convert($response, CpuResult::class . '[]');

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(CpuResult::class, $result);
        $this->assertFalse($result->isCustom());
    }
}
