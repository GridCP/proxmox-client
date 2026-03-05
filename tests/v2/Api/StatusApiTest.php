<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Tests\Api;

use GridCP\Proxmox\Api\Api\Parameters\MigrationType;
use GridCP\Proxmox\Api\Api\Parameters\StartParameters;
use GridCP\Proxmox\Api\Api\Parameters\StopParameters;
use GridCP\Proxmox\Api\Api\Parameters\SuspendParameters;
use GridCP\Proxmox\Api\Api\StatusApi;
use GridCP\Proxmox\Api\ProxmoxApiClient;
use GridCP\Proxmox\Api\Result\CurrentResult;
use GridCP\Proxmox\Api\Result\RebootResult;
use GridCP\Proxmox\Api\Result\ResetResult;
use GridCP\Proxmox\Api\Result\ResumeResult;
use GridCP\Proxmox\Api\Result\ShoutdownResult;
use GridCP\Proxmox\Api\Result\StartResult;
use GridCP\Proxmox\Api\Result\StatusResult;
use GridCP\Proxmox\Api\Result\StopResult;
use GridCP\Proxmox\Api\Result\SuspendResult;
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

    public function testResetWithParameters(): void
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
            ->with('/api2/json/nodes/nodeName/qemu/vmId/status/shutdown')
            ->willReturn($response);

        $api = new StatusApi($apiClient, 'nodeName', 'vmId');
        $actual = $api->shoutdown();

        $this->assertInstanceOf(ShoutdownResult::class, $actual);
    }

    public function testShutdownWithParameters(): void
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
            ->with('/api2/json/nodes/nodeName/qemu/vmId/status/shutdown?forceStop=1&keepActive=1&skiplock=1&timeout=250')
            ->willReturn($response);

        $api = new StatusApi($apiClient, 'nodeName', 'vmId');
        $actual = $api->shoutdown(true, true, true, 250);

        $this->assertInstanceOf(ShoutdownResult::class, $actual);
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

    public function testStartWithParameters(): void
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
            ->with('/api2/json/nodes/nodeName/qemu/vmId/status/start?force-cpu=kvm64&machine=q35&migratedfrom=migratedfrom&migration_network=migration_network&migration_type=secure&nets-host-mtu=nets-host-mtu&skiplock=1&stateuri=stateuri&targetstorage=targetstorage&timeout=300&with-conntrack-state=0')
            ->willReturn($response);

        $api = new StatusApi($client, 'nodeName', 'vmId');
        $parameters = new StartParameters()
            ->forceCpu('kvm64')
            ->machine('q35')
            ->migratedFrom('migratedfrom')
            ->migrationNetwork('migration_network')
            ->migrationType(MigrationType::SECURE)
            ->netsHostMtu('nets-host-mtu')
            ->skipLock(true)
            ->stateUri('stateuri')
            ->targetStorage('targetstorage')
            ->timeout(300)
            ->withConntrackState(false);

        $actual = $api->start($parameters);

        $this->assertInstanceOf(StartResult::class, $actual);
    }

    public function testStop(): void
    {
        $apiClient = $this->createMock(ProxmoxApiClient::class);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"data": "uuid-test"}');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);

        $apiClient->expects($this->once())
            ->method('post')
            ->with('/api2/json/nodes/nodeName/qemu/vmId/status/stop')
            ->willReturn($response);

        $api = new StatusApi($apiClient, 'nodeName', 'vmId');
        $actual = $api->stop();

        $this->assertInstanceOf(StopResult::class, $actual);
    }

    public function testStopWithParameters(): void
    {
        $apiClient = $this->createMock(ProxmoxApiClient::class);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"data": "uuid-test"}');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);

        $apiClient->expects($this->once())
            ->method('post')
            ->with('/api2/json/nodes/nodeName/qemu/vmId/status/stop?keepActive=1&migratedfrom=migratedfrom&overrule-shutdown=0&skiplock=0&timeout=100')
            ->willReturn($response);

        $api = new StatusApi($apiClient, 'nodeName', 'vmId');
        $parameters = new StopParameters()
            ->keepActive(true)
            ->migratedFrom('migratedfrom')
            ->overruleShutdown(false)
            ->skipLock(false)
            ->timeout(100);

        $actual = $api->stop($parameters);

        $this->assertInstanceOf(StopResult::class, $actual);
    }

    public function testSuspend(): void
    {
        $apiClient = $this->createMock(ProxmoxApiClient::class);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"data": "uuid-test"}');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);

        $apiClient->expects($this->once())
            ->method('post')
            ->with('/api2/json/nodes/nodeName/qemu/vmId/status/suspend')
            ->willReturn($response);

        $api = new StatusApi($apiClient, 'nodeName', 'vmId');

        $actual = $api->suspend();

        $this->assertInstanceOf(SuspendResult::class, $actual);
    }

    public function testSuspendWithParameters(): void
    {
        $apiClient = $this->createMock(ProxmoxApiClient::class);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"data": "uuid-test"}');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);

        $apiClient->expects($this->once())
            ->method('post')
            ->with('/api2/json/nodes/nodeName/qemu/vmId/status/suspend?skiplock=1&statestorage=local-lvm&todisk=0')
            ->willReturn($response);

        $api = new StatusApi($apiClient, 'nodeName', 'vmId');

        $parameters = new SuspendParameters()
            ->skipLock(true)
            ->stateStorage('local-lvm')
            ->toDisk(false);

        $actual = $api->suspend($parameters);

        $this->assertInstanceOf(SuspendResult::class, $actual);
    }
}
