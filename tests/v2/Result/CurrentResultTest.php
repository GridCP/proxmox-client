<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result;

use GridCP\Proxmox\Result\CurrentResult;
use GridCP\Proxmox\Result\ResultConverter;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CurrentResultTest extends TestCase
{
    public function testNormalizeCurrentResponseToDto(): void
    {
        $converter = new ResultConverter();

        $json = json_encode([
            'data' => [
                'ha' => ['state' => 'running'],
                'status' => 'running',
                'vmid' => 100,
                'agent' => true,
                'clipboard' => 'vnc',
                'cpu' => 0.12,
                'cpus' => 4,
                'diskread' => 123456,
                'diskwrite' => 654321,
                'lock' => 'backup',
                'maxdisk' => 10737418240,
                'maxmem' => 2147483648,
                'mem' => 123456789,
                'memhost' => 987654321,
                'name' => 'vm-example',
                'netin' => 1111,
                'netout' => 2222,
                'pid' => 9999,
                'pressurecpufull' => 0.1,
                'pressurecpusome' => 0.2,
                'pressureiofull' => 0.3,
                'pressureiosome' => 0.4,
                'pressurememoryfull' => 0.5,
                'pressurememorysome' => 0.6,
                'qmpstatus' => 'running',
                'running-machine' => 'q35',
                'running-qemu' => '8.1.0',
                'serial' => true,
                'spice' => true,
                'tags' => 'prod;web',
                'template' => false,
                'uptime' => 3600,
            ],
        ], JSON_THROW_ON_ERROR);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $result = $converter->convert($response, CurrentResult::class);

        $this->assertInstanceOf(CurrentResult::class, $result);
        $this->assertSame('running', $result->status);
        $this->assertSame(100, $result->vmId);
        $this->assertSame(0.6, $result->pressureMemorySome);
        $this->assertTrue($result->agent);
        $this->assertSame('q35', $result->runningMachine);
        $this->assertSame('8.1.0', $result->runningQemu);
        $this->assertSame(3600, $result->uptime);
    }
}

