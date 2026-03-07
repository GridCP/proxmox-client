<?php

namespace GridCP\Proxmox\Api\Tests\Api\Parameters;

use GridCP\Proxmox\Api\Api\Parameters\DestroyParameters;
use PHPUnit\Framework\TestCase;

class DestroyParametersTest extends TestCase
{
    public function testWithAllMethods(): void
    {
        $parameters = new DestroyParameters()
            ->destroyUnreferencedDisks(true)
            ->purge(true)
            ->skipLock(false);

        $expected = [
            'destroy-unreferenced-disks' => true,
            'purge' => true,
            'skiplock' => false,
        ];

        $this->assertSame(
            $expected,
            $parameters->toArray(),
        );
    }

    public function testEmptyParameters(): void
    {
        $parameters = new DestroyParameters();

        $this->assertSame([], $parameters->toArray());
    }
}
