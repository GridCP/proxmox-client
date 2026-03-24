<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result\Storage;

use GridCP\Proxmox\Result\ResultConverter;
use GridCP\Proxmox\Result\Storage\NodeStorageResult;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class NodeStorageResultTest extends TestCase
{
    public function testNormalizeResponseToDto(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $json = json_encode([
            'data' => [
                'used' => 153235042304,
                'avail' => 7845925539840,
                'content' => 'backup',
                'used_fraction' => 0.0191563903150109,
                'shared' => 1,
                'total' => 7999160582144,
                'type' => 'pbs',
                'enabled' => 1,
                'storage' => 'migraciones',
                'active' => 1,
            ],
        ], JSON_THROW_ON_ERROR);

        $stream->method('getContents')->willReturn($json);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getReasonPhrase')->willReturn('OK');
        $response->method('getBody')->willReturn($stream);

        $converter = new ResultConverter();
        $result = $converter->convert($response, NodeStorageResult::class);

        $this->assertInstanceOf(NodeStorageResult::class, $result);
        $this->assertSame(153235042304, $result->used);
        $this->assertSame(7845925539840, $result->avail);
        $this->assertSame('backup', $result->content);
        $this->assertSame(0.0191563903150109, $result->usedFraction);
        $this->assertSame(1, $result->shared);
        $this->assertSame(7999160582144, $result->total);
        $this->assertSame('pbs', $result->type);
        $this->assertSame(1, $result->enabled);
        $this->assertSame('migraciones', $result->storage);
        $this->assertSame(1, $result->active);
    }
}
