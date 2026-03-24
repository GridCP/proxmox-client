<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api;

use GridCP\Proxmox\Api\Parameters\Storage\ListStorageParameters;
use GridCP\Proxmox\Api\StorageApi;
use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\Storage\StatusResult;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class StorageApiTest extends TestCase
{
    public function testListWithParameters(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn(json_encode([
                'data' => [
                    [
                        'used' => 0,
                        'avail' => 0,
                        'content' => 'images,rootdir',
                        'shared' => 0,
                        'total' => 0,
                        'type' => 'lvmthin',
                        'enabled' => 1,
                        'storage' => 'nvme',
                        'active' => 0,
                    ],
                    [
                        'avail' => 7845925539840,
                        'used' => 153235042304,
                        'content' => 'backup',
                        'total' => 7999160582144,
                        'shared' => 1,
                        'used_fraction' => 0.0191563903150109,
                        'enabled' => 1,
                        'type' => 'pbs',
                        'active' => 1,
                        'storage' => 'migraciones',
                    ],
                    [
                        'active' => 1,
                        'storage' => 'local',
                        'enabled' => 1,
                        'type' => 'dir',
                        'total' => 64183046144,
                        'shared' => 0,
                        'used_fraction' => 0.079319825683841,
                        'content' => 'images,vztmpl,iso,rootdir,backup,snippets',
                        'avail' => 55798480896,
                        'used' => 5090988032,
                    ],
                    [
                        'total' => 695784701952,
                        'shared' => 1,
                        'used_fraction' => 0.350950264636381,
                        'storage' => 'nfs-iso',
                        'active' => 1,
                        'enabled' => 1,
                        'type' => 'nfs',
                        'used' => 244185825280,
                        'avail' => 451598876672,
                        'content' => 'backup,images,iso',
                    ],
                    [
                        'content' => 'backup,images,iso',
                        'used' => 2142208,
                        'avail' => 373763248128,
                        'active' => 1,
                        'storage' => 'backup',
                        'type' => 'dir',
                        'enabled' => 1,
                        'used_fraction' => 5.43919288118436e-06,
                        'shared' => 0,
                        'total' => 393846669312,
                    ],
                    [
                        'type' => 'pbs',
                        'enabled' => 1,
                        'storage' => 'pbs',
                        'active' => 1,
                        'shared' => 1,
                        'used_fraction' => 0.703232325515175,
                        'total' => 32003565027328,
                        'content' => 'backup',
                        'avail' => 9497623568384,
                        'used' => 22505941458944,
                    ],
                ],
            ], JSON_THROW_ON_ERROR));
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);

        $client->expects($this->once())
            ->method('get')
            ->with('/api2/json/nodes/nodeName/storage?content=content')
            ->willReturn($response);

        $parameters = new ListStorageParameters()
            ->content('content');

        $api = new StorageApi($client, 'nodeName');
        $result = $api->list($parameters);

        $this->assertCount(6, $result);
    }

    public function testStatus(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn(json_encode([
                'data' => [
                    'active' => 1,
                    'type' => 'dir',
                    'enabled' => 1,
                    'shared' => 0,
                    'total' => 64183046144,
                    'content' => 'iso,rootdir,images,vztmpl,backup,snippets',
                    'used' => 5090951168,
                    'avail' => 55798517760,
                ],
            ], JSON_THROW_ON_ERROR));
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);

        $client->expects($this->once())
            ->method('get')
            ->with('/api2/json/nodes/nodeName/storage/storageName/status')
            ->willReturn($response);

        $api = new StorageApi($client, 'nodeName', 'storageName');
        $result = $api->status();

        $this->assertInstanceOf(StatusResult::class, $result);
    }
}
