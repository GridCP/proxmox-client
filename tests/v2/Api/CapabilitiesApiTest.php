<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api;

use GridCP\Proxmox\Api\CapabilitiesApi;
use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\Capabilities\CpuResult;
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
