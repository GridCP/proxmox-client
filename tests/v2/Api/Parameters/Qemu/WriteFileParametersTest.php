<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api\Parameters\Qemu;

use GridCP\Proxmox\Api\Parameters\Qemu\WriteFileParameters;
use PHPUnit\Framework\TestCase;

class WriteFileParametersTest extends TestCase
{
    public function testWithAllMethods(): void
    {
        $parameters = new WriteFileParameters()
            ->content('hello world')
            ->file('/tmp/example.txt')
            ->encode('base64');

        $expected = [
            'content' => 'hello world',
            'file' => '/tmp/example.txt',
            'encode' => 'base64',
        ];

        $this->assertSame($expected, $parameters->toArray());
    }
}
