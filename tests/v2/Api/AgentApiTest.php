<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Api;

use GridCP\Proxmox\Api\AgentApi;
use GridCP\Proxmox\Api\Parameters\Qemu\Agent\ExecParameters;
use GridCP\Proxmox\Api\Parameters\Qemu\Agent\ExecStatusParameters;
use GridCP\Proxmox\Api\Parameters\Qemu\Agent\WriteFileParameters;
use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\Qemu\ExecResult;
use GridCP\Proxmox\Result\Qemu\ExecStatusResult;
use GridCP\Proxmox\Result\Qemu\FileWriteResult;
use GridCP\Proxmox\Result\ResultConverterInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class AgentApiTest extends TestCase
{
    public function testExecWithParameters(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);
        $converter = $this->createMock(ResultConverterInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $client->expects($this->once())
            ->method('post')
            ->with(
                '/api2/json/nodes/nodeName/qemu/101/agent/exec',
                ['Content-Type' => 'application/x-www-form-urlencoded'],
                'command=c%3A%5Cgridcp.bat&input-data=--format%20json',
            )
            ->willReturn($response);

        $converter->expects($this->once())
            ->method('convert')
            ->with($response, ExecResult::class)
            ->willReturn(new ExecResult(9876));

        $parameters = new ExecParameters()
            ->command('c:\gridcp.bat')
            ->inputData('--format json');

        $api = new AgentApi($client, 'nodeName', 101, $converter);
        $actual = $api->exec($parameters);

        $this->assertInstanceOf(ExecResult::class, $actual);
        $this->assertSame(9876, $actual->pid);
    }

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

    public function testExecStatusWithParameters(): void
    {
        $client = $this->createMock(ProxmoxApiClient::class);
        $converter = $this->createMock(ResultConverterInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $client->expects($this->once())
            ->method('get')
            ->with('/api2/json/nodes/nodeName/qemu/101/agent/exec-status?pid=7421')
            ->willReturn($response);

        $execStatusResult = new ExecStatusResult(
            false,
            null,
            null,
            1,
            'done',
            false,
            null,
        );
        $converter->expects($this->once())
            ->method('convert')
            ->with($response, ExecStatusResult::class)
            ->willReturn($execStatusResult);

        $parameters = new ExecStatusParameters()
            ->pid(7421);

        $api = new AgentApi($client, 'nodeName', 101, $converter);
        $actual = $api->execStatus($parameters);

        $this->assertInstanceOf(ExecStatusResult::class, $actual);
        $this->assertTrue($actual->isSuccess());
        $this->assertFalse($actual->isError());
    }
}
