<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api\Parameters;

use GridCP\Proxmox\Api\Parameters\ConfigureParameters;
use PHPUnit\Framework\TestCase;

class ConfigureParametersTest extends TestCase
{
    public function testWithCommonMethods(): void
    {
        $parameters = new ConfigureParameters()
            ->keyboard('es')
            ->localtime(true)
            ->memory(4096)
            ->cores(4)
            ->name('debian12')
            ->machine('q35')
            ->onboot(true)
            ->ostype('l26')
            ->bios('ovmf')
            ->ciuser('tester')
            ->cipassword('secret')
            ->net0('virtio=DE:AD:BE:EF:00:01,bridge=vmbr0')
            ->ipconfig0('ip=192.0.2.10/24,gw=192.0.2.1')
            ->digest('digest-value')
            ->delete('net1')
            ->skipLock(false)
            ->parameter('tags', 'debian,api');

        $expected = [
            'keyboard' => 'es',
            'localtime' => true,
            'memory' => 4096,
            'cores' => 4,
            'name' => 'debian12',
            'machine' => 'q35',
            'onboot' => true,
            'ostype' => 'l26',
            'bios' => 'ovmf',
            'ciuser' => 'tester',
            'cipassword' => 'secret',
            'net0' => 'virtio=DE:AD:BE:EF:00:01,bridge=vmbr0',
            'ipconfig0' => 'ip=192.0.2.10/24,gw=192.0.2.1',
            'digest' => 'digest-value',
            'delete' => 'net1',
            'skiplock' => false,
            'tags' => 'debian,api',
        ];

        $this->assertSame($expected, $parameters->toArray());
    }

    public function testWithIndexedMethods(): void
    {
        $parameters = (new ConfigureParameters())
            ->net(1, 'virtio=DE:AD:BE:EF:00:02,bridge=vmbr1')
            ->ipconfig(1, 'ip=198.51.100.10/24,gw=198.51.100.1');

        $this->assertSame([
            'net1' => 'virtio=DE:AD:BE:EF:00:02,bridge=vmbr1',
            'ipconfig1' => 'ip=198.51.100.10/24,gw=198.51.100.1',
        ], $parameters->toArray());
    }
}
