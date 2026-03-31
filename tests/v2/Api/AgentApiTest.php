<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api;

use GridCP\Proxmox\Api\AgentApi;
use GridCP\Proxmox\Api\Parameters\Qemu\WriteFileParameters;
use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\Qemu\FileWriteResult;
use GridCP\Proxmox\Result\ResultConverterInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class AgentApiTest extends TestCase
{
    public function testWriteFileWithParameters(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);
        $converter = $this->createMock(ResultConverterInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $client->expects($this->once())
            ->method('post')
            ->with(
                '/api2/json/nodes/nodeName/qemu/101/agent/file-write',
                ['Content-Type' => 'application/x-www-form-urlencoded'],
                'content=hello%20world&file=%2Ftmp%2Fexample.txt&encode=base64',
            )
            ->willReturn($response);

        $converter->expects($this->once())
            ->method('convert')
            ->with($response, FileWriteResult::class)
            ->willReturn(new FileWriteResult(null));

        $writeFileParameters = new WriteFileParameters()
            ->content('hello world')
            ->file('/tmp/example.txt')
            ->encode('base64');

        $api = new AgentApi($client, 'nodeName', 101, $converter);
        $actual = $api->writeFile($writeFileParameters);

        $this->assertInstanceOf(FileWriteResult::class, $actual);
        $this->assertSame(null, $actual->result);
    }
}
