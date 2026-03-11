<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api\Parameters;

use GridCP\Proxmox\Api\Parameters\ResetParameters;
use PHPUnit\Framework\TestCase;

class ResetParametersTest extends TestCase
{
    public function testWithAllMethods(): void
    {
        $parameters = new ResetParameters()
            ->skipLock(true);

        $expected = [
            'skiplock' => true,
        ];

        $this->assertSame(
            $expected,
            $parameters->toArray(),
        );
    }
}

