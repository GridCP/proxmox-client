<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result;

use GridCP\Proxmox\Result\ConfigResult;
use GridCP\Proxmox\Result\ResultConverter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ConfigResultTest extends TestCase
{
    public function testNormalizeConfigResponseToDto(): void
    {
        $converter = new ResultConverter();

        $json = json_encode([
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
        ], JSON_THROW_ON_ERROR);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getReasonPhrase')->willReturn('OK');
        $response->method('getBody')->willReturn($stream);

        $result = $converter->convert($response, ConfigResult::class);

        $this->assertInstanceOf(ConfigResult::class, $result);
        $this->assertSame('order=scsi0', $result->boot);
        $this->assertSame('2048', $result->memory);
        $this->assertSame(2, $result->cores);
        $this->assertSame('Beta', $result->name);
        $this->assertSame('enabled=1', $result->agent);
        $this->assertSame('virtio-scsi-pci', $result->scsihw);
    }
}
