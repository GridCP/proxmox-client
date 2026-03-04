<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Tests\Api;

use GridCP\Proxmox\Api\Api\StatusApi;
use GridCP\Proxmox\Api\ProxmoxApiClient;
use GridCP\Proxmox\Api\Result\CurrentResult;
use GridCP\Proxmox\Api\Result\RebootResult;
use GridCP\Proxmox\Api\Result\ResetResult;
use GridCP\Proxmox\Api\Result\ResumeResult;
use GridCP\Proxmox\Api\Result\StartResult;
use GridCP\Proxmox\Api\Result\StatusResult;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class StatusApiTest extends TestCase
{
    public function testStatus(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"data": {"status": "running"}}');
        $response->method('getBody')
            ->willReturn($stream);

        $client->expects($this->once())
            ->method('get')
            ->with('/api2/json/nodes/nodeName/qemu/vmId/status', [])
            ->willReturn($response);

        $api = new StatusApi($client, 'nodeName', 'vmId');
        $actual = $api->status();

        $this->assertInstanceOf(StatusResult::class, $actual);
    }

    public function testCurrent(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"data": {"status": "running"}}');
        $response->method('getBody')
            ->willReturn($stream);

        $client->expects($this->once())
            ->method('get')
            ->with('/api2/json/nodes/nodeName/qemu/vmId/status/current')
            ->willReturn($response);

        $api = new StatusApi($client, 'nodeName', 'vmId');
        $actual = $api->current();

        $this->assertInstanceOf(CurrentResult::class, $actual);
    }

    public function testReboot(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"data": "uuid-test"}');
        $response->method('getBody')
            ->willReturn($stream);

        $client->expects($this->once())
            ->method('post')
            ->with('/api2/json/nodes/nodeName/qemu/vmId/status/reboot')
            ->willReturn($response);

        $api = new StatusApi($client, 'nodeName', 'vmId');

        $actual = $api->reboot();

        $this->assertInstanceOf(RebootResult::class, $actual);
    }

    public function testRebootWithTimeout(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"data": "uuid-test"}');
        $response->method('getBody')
            ->willReturn($stream);

        $client->expects($this->once())
            ->method('post')
            ->with('/api2/json/nodes/nodeName/qemu/vmId/status/reboot?timeout=30')
            ->willReturn($response);

        $api = new StatusApi($client, 'nodeName', 'vmId');

        $actual = $api->reboot(30);

        $this->assertInstanceOf(RebootResult::class, $actual);
    }

    public function testReset(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"data": "uuid-test"}');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);

        $client->expects($this->once())
            ->method('post')
            ->with('/api2/json/nodes/nodeName/qemu/vmId/status/reset')
            ->willReturn($response);

        $api = new StatusApi($client, 'nodeName', 'vmId');
        $actual = $api->reset();

        $this->assertInstanceOf(ResetResult::class, $actual);
    }

    public function testResetWithSkiplock(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"data": "uuid-test"}');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);

        $client->expects($this->once())
            ->method('post')
            ->with('/api2/json/nodes/nodeName/qemu/vmId/status/reset?skiplock=1')
            ->willReturn($response);

        $api = new StatusApi($client, 'nodeName', 'vmId');
        $actual = $api->reset(true);

        $this->assertInstanceOf(ResetResult::class, $actual);
    }

    public function testResume(): void
    {
        $apiClient = $this->createMock(ProxmoxApiClient::class);

        $stream = $this->createMock(StreamInterface::class);
        $stream->expects($this->once())
            ->method('getContents')
            ->willReturn('{"data": "uuid-test"}');

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $apiClient->expects($this->once())
            ->method('post')
            ->with('/api2/json/nodes/nodeName/qemu/vmId/status/resume')
            ->willReturn($response);

        $api = new StatusApi($apiClient, 'nodeName', 'vmId');
        $actual = $api->resume();

        $this->assertInstanceOf(ResumeResult::class, $actual);
    }

    public function testResumeWithParameters(): void
    {
        $apiClient = $this->createMock(ProxmoxApiClient::class);

        $stream = $this->createMock(StreamInterface::class);
        $stream->expects($this->once())
            ->method('getContents')
            ->willReturn('{"data": "uuid-test"}');

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $apiClient->expects($this->once())
            ->method('post')
            ->with('/api2/json/nodes/nodeName/qemu/vmId/status/resume?nocheck=1&skiplock=1')
            ->willReturn($response);

        $api = new StatusApi($apiClient, 'nodeName', 'vmId');
        $actual = $api->resume(true, true);

        $this->assertInstanceOf(ResumeResult::class, $actual);
    }

    public function testShutdown(): void
    {
        $this->markTestSkipped('Test not implemented yet.');
    }

    public function testStart(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"data": "uuid-test"}');
        $response->method('getBody')
            ->willReturn($stream);

        $client->expects($this->once())
            ->method('post')
            ->with('/api2/json/nodes/nodeName/qemu/vmId/status/start')
            ->willReturn($response);

        $api = new StatusApi($client, 'nodeName', 'vmId');
        $actual = $api->start();

        $this->assertInstanceOf(StartResult::class, $actual);
    }

    public function testStop(): void
    {
        $this->markTestSkipped('Test not implemented yet.');
    }

    public function testSuspend(): void
    {
        $this->markTestSkipped('Test not implemented yet.');
    }
}
