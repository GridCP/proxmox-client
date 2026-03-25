<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api\Parameters;

use GridCP\Proxmox\Api\Parameters\ResizeParameters;
use PHPUnit\Framework\TestCase;

class ResizeParametersTest extends TestCase
{
    public function testWithAllMethods(): void
    {
        $parameters = new ResizeParameters()
            ->disk(1, 'scsi')
            ->size('10G')
            ->digest('sha256:1234567890')
            ->skipLock(true);

        $expected = [
            'disk' => 'scsi1',
            'size' => '10G',
            'digest' => 'sha256:1234567890',
            'skiplock' => true,
        ];

        $this->assertSame($expected, $parameters->toArray());
    }

    public function test(): void
    {
        $parameters = new ResizeParameters()
            ->disk(0, 'scsi')
            ->size('+1M');

        $expected = [
            'disk' => 'scsi0',
            'size' => '+1M',
        ];

        $this->assertSame($expected, $parameters->toArray());
    }
}
