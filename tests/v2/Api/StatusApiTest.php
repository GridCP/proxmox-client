<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api;

use GridCP\Proxmox\Api\Parameters\MigrationType;
use GridCP\Proxmox\Api\Parameters\StartParameters;
use GridCP\Proxmox\Api\Parameters\StopParameters;
use GridCP\Proxmox\Api\Parameters\SuspendParameters;
use GridCP\Proxmox\Api\StatusApi;
use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\CurrentResult;
use GridCP\Proxmox\Result\RebootResult;
use GridCP\Proxmox\Result\ResetResult;
use GridCP\Proxmox\Result\ResumeResult;
use GridCP\Proxmox\Result\ShoutdownResult;
use GridCP\Proxmox\Result\StartResult;
use GridCP\Proxmox\Result\StatusResult;
use GridCP\Proxmox\Result\StopResult;
use GridCP\Proxmox\Result\SuspendResult;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class StatusApiTest extends TestCase
{
    public function testStatus(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn('{"data": {"status": "running"}}');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);

        $client->expects($this->once())
            ->method('get')
            ->with('/api2/json/nodes/nodeName/qemu/100/status', [])
            ->willReturn($response);

        $api = new StatusApi($client, 'nodeName', 100);
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
            ->with('/api2/json/nodes/nodeName/qemu/100/status/current')
            ->willReturn($response);

        $api = new StatusApi($client, 'nodeName', 100);
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
            ->with('/api2/json/nodes/nodeName/qemu/100/status/reboot')
            ->willReturn($response);

        $api = new StatusApi($client, 'nodeName', 100);

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
            ->with('/api2/json/nodes/nodeName/qemu/100/status/reboot?timeout=30')
            ->willReturn($response);

        $api = new StatusApi($client, 'nodeName', 100);

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
            ->with('/api2/json/nodes/nodeName/qemu/100/status/reset')
            ->willReturn($response);

        $api = new StatusApi($client, 'nodeName', 100);
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
            ->with('/api2/json/nodes/nodeName/qemu/100/status/reset?skiplock=1')
            ->willReturn($response);

        $api = new StatusApi($client, 'nodeName', 100);
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
            ->with('/api2/json/nodes/nodeName/qemu/100/status/resume')
            ->willReturn($response);

        $api = new StatusApi($apiClient, 'nodeName', 100);
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
            ->with('/api2/json/nodes/nodeName/qemu/100/status/resume?nocheck=1&skiplock=1')
            ->willReturn($response);

        $api = new StatusApi($apiClient, 'nodeName', 100);
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
            ->with('/api2/json/nodes/nodeName/qemu/100/status/shutdown')
            ->willReturn($response);

        $api = new StatusApi($apiClient, 'nodeName', 100);
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
            ->with('/api2/json/nodes/nodeName/qemu/100/status/shutdown?forceStop=1&keepActive=1&skiplock=1&timeout=250')
            ->willReturn($response);

        $api = new StatusApi($apiClient, 'nodeName', 100);
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
            ->with('/api2/json/nodes/nodeName/qemu/100/status/start')
            ->willReturn($response);

        $api = new StatusApi($client, 'nodeName', 100);
        $actual = $api->start();

        $this->assertInstanceOf(StartResult::class, $actual);
    }

    public function testStartWithParameters(): void
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
            ->with('/api2/json/nodes/nodeName/qemu/100/status/start?force-cpu=kvm64&machine=q35&migratedfrom=migratedfrom&migration_network=migration_network&migration_type=secure&nets-host-mtu=nets-host-mtu&skiplock=1&stateuri=stateuri&targetstorage=targetstorage&timeout=300&with-conntrack-state=0')
            ->willReturn($response);

        $api = new StatusApi($client, 'nodeName', 100);
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
            ->with('/api2/json/nodes/nodeName/qemu/100/status/stop')
            ->willReturn($response);

        $api = new StatusApi($apiClient, 'nodeName', 100);
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
            ->with('/api2/json/nodes/nodeName/qemu/100/status/stop?keepActive=1&migratedfrom=migratedfrom&overrule-shutdown=0&skiplock=0&timeout=100')
            ->willReturn($response);

        $api = new StatusApi($apiClient, 'nodeName', 100);
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
            ->with('/api2/json/nodes/nodeName/qemu/100/status/suspend')
            ->willReturn($response);

        $api = new StatusApi($apiClient, 'nodeName', 100);

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
            ->with('/api2/json/nodes/nodeName/qemu/100/status/suspend?skiplock=1&statestorage=local-lvm&todisk=0')
            ->willReturn($response);

        $api = new StatusApi($apiClient, 'nodeName', 100);

        $parameters = new SuspendParameters()
            ->skipLock(true)
            ->stateStorage('local-lvm')
            ->toDisk(false);

        $actual = $api->suspend($parameters);

        $this->assertInstanceOf(SuspendResult::class, $actual);
    }
}
