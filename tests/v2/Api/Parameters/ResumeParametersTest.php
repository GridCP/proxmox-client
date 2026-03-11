<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api\Parameters;

use GridCP\Proxmox\Api\Parameters\ResumeParameters;
use PHPUnit\Framework\TestCase;

class ResumeParametersTest extends TestCase
{
    public function testEmptyParameters(): void
    {
        $parameters = new ResumeParameters();

        $this->assertSame([], $parameters->toArray());
    }

    public function testWithAllMethods(): void
    {
        $parameters = new ResumeParameters()
            ->noCheck(true)
            ->skipLock(true);

        $expected = [
            'nocheck' => true,
            'skiplock' => true,
        ];

        $this->assertSame(
            $expected,
            $parameters->toArray(),
        );
    }
}
