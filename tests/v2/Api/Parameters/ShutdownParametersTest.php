<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api\Parameters;

use GridCP\Proxmox\Api\Parameters\ShutdownParameters;
use PHPUnit\Framework\TestCase;

class ShutdownParametersTest extends TestCase
{
    public function testWithAllMethods(): void
    {
        $parameters = new ShutdownParameters()
            ->forceStop(true)
            ->keepActive(true)
            ->skipLock(true)
            ->timeout(250);

        $expected = [
            'forceStop' => true,
            'keepActive' => true,
            'skiplock' => true,
            'timeout' => 250,
        ];

        $this->assertSame(
            $expected,
            $parameters->toArray(),
        );
    }
}
