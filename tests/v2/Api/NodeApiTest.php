<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api;

use GridCP\Proxmox\Api\NodeApi;
use GridCP\Proxmox\Api\StorageApi;
use GridCP\Proxmox\ProxmoxApiClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class NodeApiTest extends TestCase
{
    public function testNodes()
    {
        $client = $this->createMock(ProxmoxApiClient::class);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn(json_encode(
                [
                    'data' => [
                        [
                            'cpu' => 0.00133071625802588,
                            'mem' => 3104186368,
                            'node' => 'ns2202',
                            'maxdisk' => 64183046144,
                            'maxmem' => 134954295296,
                            'level' => '',
                            'maxcpu' => 32,
                            'id' => 'node/ns2202',
                            'type' => 'node',
                            'uptime' => 181149,
                            'status' => 'online',
                            'disk' => 5043159040,
                            'ssl_fingerprint' => '38:B7:74:83:C7:66:21:D8:77:62:68:A2:8B:79:04:26:FC:C6:40:FF:7F:F3:4E:4A:C1:A4:AF:B7:0A:FB:03:9E',
                        ],
                    ],
                ]
            ));
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')
            ->willReturn(200);
        $response->method('getReasonPhrase')
            ->willReturn('OK');
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $client->expects($this->once())
            ->method('get')
            ->with('/api2/json/nodes')
            ->willReturn($response);

        $api = new NodeApi($client);
        $actual = $api->nodes();

        $this->assertCount(1, $actual);
        $this->assertSame('ns2202', $actual[0]->node);
    }

    public function testQemuRequiresNode()
    {
        $client = $this->createMock(ProxmoxApiClient::class);
        $api = new NodeApi($client);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Node name is required for node-scoped endpoints');

        $api->qemu(100);
    }

    public function testStorage(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);
        $api = new NodeApi($client);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Node name is required for node-scoped endpoints');

        $api->storage('localStorage');
    }

    public function testStorageInstantiation(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);
        $api = new NodeApi($client, 'nodeName');

        $actual = $api->storage('localStorage');

        $this->assertInstanceOf(StorageApi::class, $actual);
    }

    public function testTasksRequiresNode()
    {
        $client = $this->createMock(ProxmoxApiClient::class);
        $api = new NodeApi($client);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Node name is required for node-scoped endpoints');

        $api->tasks('UPID:test');
    }
}
