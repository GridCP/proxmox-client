<?php

namespace GridCP\Proxmox\Tests\Api\Parameters;

use GridCP\Proxmox\Api\Parameters\MigrationType;
use GridCP\Proxmox\Api\Parameters\StartParameters;
use PHPUnit\Framework\TestCase;

class StartParametersTest extends TestCase
{
    public function testWithAllMethods(): void
    {
        $parameters = (new StartParameters())
            ->forceCpu('kvm64')
            ->machine('q35')
            ->migratedFrom('source-node')
            ->migrationNetwork('mig-net')
            ->migrationType(MigrationType::SECURE)
            ->netsHostMtu('1500')
            ->skipLock(true)
            ->stateUri('nbd://10.0.0.1/state')
            ->targetStorage('local-lvm')
            ->timeout(300)
            ->withConntrackState(false);

        $expected = [
            'force-cpu' => 'kvm64',
            'machine' => 'q35',
            'migratedfrom' => 'source-node',
            'migration_network' => 'mig-net',
            'migration_type' => MigrationType::SECURE,
            'nets-host-mtu' => '1500',
            'skiplock' => true,
            'stateuri' => 'nbd://10.0.0.1/state',
            'targetstorage' => 'local-lvm',
            'timeout' => 300,
            'with-conntrack-state' => false,
        ];

        $this->assertSame(
            $expected,
            $parameters->toArray(),
        );
    }
}
