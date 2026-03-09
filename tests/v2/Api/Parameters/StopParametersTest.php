<?php

namespace GridCP\Proxmox\Tests\Api\Parameters;

use GridCP\Proxmox\Api\Parameters\StopParameters;
use PHPUnit\Framework\TestCase;

class StopParametersTest extends TestCase
{
    public function testWithAllMethods(): void
    {
        $parameters = (new StopParameters())
            ->keepActive(true)
            ->migratedFrom('migratedfrom')
            ->overruleShutdown(true)
            ->skipLock(false)
            ->timeout(100)
        ;

        $expected = [
            'keepActive' => true,
            'migratedfrom' => 'migratedfrom',
            'overrule-shutdown' => true,
            'skiplock' => false,
            'timeout' => 100,
        ];

        $this->assertSame(
            $expected,
            $parameters->toArray(),
        );
    }
}
