<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api\Parameters\Qemu\Agent;

use GridCP\Proxmox\Api\Parameters\Qemu\Agent\ExecStatusParameters;
use PHPUnit\Framework\TestCase;

class ExecStatusParametersTest extends TestCase
{
    public function testWithAllMethods(): void
    {
        $parameters = new ExecStatusParameters()
            ->pid(7421);

        $expected = [
            'pid' => 7421,
        ];

        $this->assertSame($expected, $parameters->toArray());
    }
}
