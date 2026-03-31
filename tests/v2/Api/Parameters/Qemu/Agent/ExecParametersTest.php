<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api\Parameters\Qemu\Agent;

use GridCP\Proxmox\Api\Parameters\Qemu\Agent\ExecParameters;
use PHPUnit\Framework\TestCase;

class ExecParametersTest extends TestCase
{
    public function testWithAllMethods(): void
    {
        $parameters = new ExecParameters()
            ->command('c:\gridcp.bat')
            ->inputData('--format json');

        $expected = [
            'command' => 'c:\gridcp.bat',
            'input-data' => '--format json',
        ];

        $this->assertSame($expected, $parameters->toArray());
    }
}
