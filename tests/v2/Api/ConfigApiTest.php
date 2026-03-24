<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api;

use GridCP\Proxmox\Api\ConfigApi;
use GridCP\Proxmox\Api\Parameters\ConfigureParameters;
use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\ConfigResult;
use GridCP\Proxmox\Result\StartResult;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ConfigApiTest extends TestCase
{
    public function testGet(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn(json_encode([
                'data' => [
                    'boot' => 'order=scsi0',
                    'memory' => '2048',
                    'parent' => 'snapshot-23032026',
                    'net0' => 'virtio=XX:XX:XX:XX:XX:XX,bridge=vmbr0,firewall=1',
                    'cores' => 2,
                    'meta' => 'creation-qemu=10.1.2,ctime=1774013914',
                    'onboot' => 1,
                    'name' => 'Beta',
                    'ostype' => 'l26',
                    'balloon' => 0,
                    'ide2' => 'nvme:vm-101-cloudinit,media=cdrom',
                    'ide0' => 'none,media=cdrom',
                    'smbios1' => 'uuid=XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',
                    'tags' => 'debian12',
                    'cipassword' => '**********',
                    'scsi0' => 'nvme:vm-101-disk-0,cache=directsync,discard=on,size=50G',
                    'ciuser' => 'testuser',
                    'keyboard' => 'es',
                    'cpu' => 'x86-64-v2-AES',
                    'digest' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
                    'ipconfig0' => 'ip=192.0.2.100/24,gw=192.0.2.1',
                    'agent' => 'enabled=1',
                    'vmgenid' => 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',
                    'scsihw' => 'virtio-scsi-pci',
                ],
            ], JSON_THROW_ON_ERROR));

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);

        $client->expects($this->once())
            ->method('get')
            ->with('/api2/json/nodes/nodeName/qemu/101/config')
            ->willReturn($response);

        $api = new ConfigApi($client, 'nodeName', 101);
        $actual = $api->get();

        $this->assertInstanceOf(ConfigResult::class, $actual);
        $this->assertSame('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', $actual->digest);
    }

    public function testSetWithData(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn(json_encode([
                'data' => 'UPID:XXXXXXXX:XXXXXXXX:XXXXXXXX:XXXXXXXX:qmconfig:101:root@pam!gridcp:',
            ], JSON_THROW_ON_ERROR));

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);

        $client->expects($this->once())
            ->method('post')
            ->with('/api2/json/nodes/nodeName/qemu/101/config?memory=4096&name=VirtualMachineName')
            ->willReturn($response);

        $api = new ConfigApi($client, 'nodeName', 101);
        $parameters = new ConfigureParameters()
            ->memory(4096)
            ->name('VirtualMachineName')
        ;

        $actual = $api->set($parameters);

        $this->assertInstanceOf(StartResult::class, $actual);
        $this->assertSame(
            'UPID:XXXXXXXX:XXXXXXXX:XXXXXXXX:XXXXXXXX:qmconfig:101:root@pam!gridcp:',
            $actual->upid,
        );
    }
}
