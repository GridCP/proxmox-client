<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Tests;

use GridCP\Proxmox\Api\ProxmoxApiClient;
use GridCP\Proxmox\Api\Result\ResultInterface;
use GridCP\Proxmox\Api\StatusApi;
use PHPUnit\Framework\TestCase;

class StatusApiTest extends TestCase
{
    public function testStart(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);
        $client->expects($this->once())
            ->method('request')
            ->with('POST', '/api2/json/nodes/{node}/qemu/{vmid}/status/start');

        $api = new StatusApi($client, 'node', 'vmid');
        $actual = $api->start();

        $this->assertInstanceOf(ResultInterface::class, $actual);
    }
}
