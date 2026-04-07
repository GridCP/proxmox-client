<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api;

use GridCP\Proxmox\Api\CapabilitiesApi;
use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\Capabilities\CpuResult;
use GridCP\Proxmox\Result\Capabilities\MachineResult;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CapabilitiesApiTest extends TestCase
{
    public function testCpu(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);
        $response = $this->createJsonResponse([
            'data' => [
                [
                    'vendor' => 'GenuineIntel',
                    'name' => 'Skylake-Server-v4',
                    'custom' => 0,
                ],
                [
                    'vendor' => 'default',
                    'name' => 'host',
                    'custom' => 0,
                ],
            ],
        ]);

        $client->expects($this->once())
            ->method('get')
            ->with('/api2/json/nodes/nodeName/capabilities/qemu/cpu')
            ->willReturn($response);

        $api = new CapabilitiesApi($client, 'nodeName');
        $result = $api->cpu();

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(CpuResult::class, $result);
        $this->assertSame('Skylake-Server-v4', $result[0]->name);
        $this->assertSame('GenuineIntel', $result[0]->vendor);
        $this->assertSame(false, $result[0]->custom);
    }

    public function testMachines(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);
        $response = $this->createJsonResponse([
            'data' => [
                [
                    'id' => 'pc-i440fx-10.1',
                    'type' => 'i440fx',
                    'version' => '10.1',
                ],
                [
                    'id' => 'pc-q35-10.1',
                    'type' => 'q35',
                    'version' => '10.1',
                ],
                [
                    'id' => 'pc-i440fx-10.0+pve1',
                    'type' => 'i440fx',
                    'version' => '10.0+pve1',
                    'changes' => 'Set host_mtu vNIC option even with default value for migration compat.',
                ],
            ],
        ]);

        $client->expects($this->once())
            ->method('get')
            ->with('/api2/json/nodes/nodeName/capabilities/qemu/machines')
            ->willReturn($response);

        $api = new CapabilitiesApi($client, 'nodeName');
        $result = $api->machines();

        $this->assertCount(3, $result);
        $this->assertContainsOnlyInstancesOf(MachineResult::class, $result);
        $this->assertSame('pc-i440fx-10.1', $result[0]->id);
        $this->assertSame('i440fx', $result[0]->type);
        $this->assertSame('10.1', $result[0]->version);
        $this->assertNull($result[0]->changes);
        $this->assertSame(
            'Set host_mtu vNIC option even with default value for migration compat.',
            $result[2]->changes
        );
    }

    private function createJsonResponse(array $payload): ResponseInterface
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn(json_encode($payload, JSON_THROW_ON_ERROR));

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);

        $response->method('getStatusCode')
            ->willReturn(200);

        return $response;
    }
}
