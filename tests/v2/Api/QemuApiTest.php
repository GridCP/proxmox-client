<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Tests\Api;

use GridCP\Proxmox\Api\Api\Parameters\DestroyParameters;
use GridCP\Proxmox\Api\Api\QemuApi;
use GridCP\Proxmox\Api\ProxmoxApiClient;
use GridCP\Proxmox\Api\Result\DestroyResult;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class QemuApiTest extends TestCase
{
    public function testDestroy(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);

        $stream = $this->createMock(StreamInterface::class);
        $stream->expects($this->once())
            ->method('getContents')
            ->willReturn('{"data": "uuid-test"}');

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $client->expects($this->once())
            ->method('delete')
            ->with('/api2/json/nodes/ns1047/qemu/100')
            ->willReturn($response);

        $api = new QemuApi($client, 'ns1047', 100);
        $actual = $api->destroy();

        $this->assertInstanceOf(DestroyResult::class, $actual);
    }

    public function testDestroyWithParameters(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);

        $stream = $this->createMock(StreamInterface::class);
        $stream->expects($this->once())
            ->method('getContents')
            ->willReturn('{"data": "uuid-test"}');

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $client->expects($this->once())
            ->method('delete')
            ->with('/api2/json/nodes/ns1047/qemu/100?destroy-unreferenced-disks=1&purge=1&skiplock=0')
            ->willReturn($response);

        $api = new QemuApi($client, 'ns1047', 100);

        $parameters = new DestroyParameters()
            ->destroyUnreferencedDisks(true)
            ->purge(true)
            ->skipLock(false);

        $actual = $api->destroy($parameters);

        $this->assertInstanceOf(DestroyResult::class, $actual);
    }
}
