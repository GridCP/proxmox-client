<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api;

use GridCP\Proxmox\Api\Parameters\ResizeParameters;
use GridCP\Proxmox\Api\ResizeApi;
use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\Qemu\ResizeResult;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResizeApiTest extends TestCase
{
    public function testResizeWithParameters(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn(json_encode([
                'data' => 'UPID:nodeName:XXXXXXXX:XXXXXXXX:XXXXXXXX:resize:101:root@pam!gridcp:',
            ], JSON_THROW_ON_ERROR));

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);

        $client->expects($this->once())
            ->method('put')
            ->with('/api2/json/nodes/nodeName/qemu/101/resize?disk=scsi0&size=%2B10G&digest=abc123&skiplock=1')
            ->willReturn($response);

        $parameters = new ResizeParameters()
            ->disk('scsi0')
            ->size('+10G')
            ->digest('abc123')
            ->skipLock(true);

        $api = new ResizeApi($client, 'nodeName', 101);
        $actual = $api->resize($parameters);

        $this->assertInstanceOf(ResizeResult::class, $actual);
        $this->assertSame('UPID:nodeName:XXXXXXXX:XXXXXXXX:XXXXXXXX:resize:101:root@pam!gridcp:', $actual->upid);
    }
}
