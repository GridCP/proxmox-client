<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Tests\Api\Parameters;

use GridCP\Proxmox\Api\Api\Parameters\SuspendParameters;
use PHPUnit\Framework\TestCase;

class SuspendParametersTest extends TestCase
{
    public function testWithAllMethods(): void
    {
        $parameters = (new SuspendParameters())
            ->skipLock(true)
            ->stateStorage('local-lvm')
            ->toDisk(false);

        $expected = [
            'skiplock' => true,
            'statestorage' => 'local-lvm',
            'todisk' => false,
        ];

        $this->assertSame(
            $expected,
            $parameters->toArray(),
        );
    }
}
