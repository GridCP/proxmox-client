<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result;

use GridCP\Proxmox\Result\CurrentResult;
use GridCP\Proxmox\Result\ResultConverter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CurrentResultTest extends TestCase
{
    public function testNormalizeCurrentResponseToDto(): void
    {
        $converter = new ResultConverter();

        $json = json_encode([
            'data' => [
                'qmpstatus' => 'stopped',
                'netout' => 0,
                'name' => 'VM 100',
                'ha' => [
                    'managed' => 0,
                ],
                'disk' => 0,
                'maxdisk' => 0,
                'cpus' => 1,
                'vmid' => 100,
                'maxmem' => 2147483648,
                'memhost' => 0,
                'mem' => 0,
                'cpu' => 0,
                'netin' => 0,
                'status' => 'stopped',
                'uptime' => 0,
            ],
        ], JSON_THROW_ON_ERROR);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getReasonPhrase')->willReturn('OK');
        $response->method('getBody')->willReturn($stream);

        $result = $converter->convert($response, CurrentResult::class);

        $this->assertInstanceOf(CurrentResult::class, $result);
        $this->assertSame('stopped', $result->qmpStatus);
        $this->assertSame('VM 100', $result->name);
        $this->assertSame(['managed' => 0], $result->ha);
        $this->assertSame(100, $result->vmId);
        $this->assertSame('stopped', $result->status);
    }
}
