<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api\Parameters;

use GridCP\Proxmox\Api\Parameters\RebootParameters;
use PHPUnit\Framework\TestCase;

class RebootParametersTest extends TestCase
{
    public function testWithAllMethods(): void
    {
        $parameters = new RebootParameters()
            ->timeout(60);

        $expected = [
            'timeout' => 60,
        ];

        $this->assertSame(
            $expected,
            $parameters->toArray(),
        );
    }
}
