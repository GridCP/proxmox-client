<?php

namespace GridCP\Proxmox\Tests\Result;

use GridCP\Proxmox\Result\ResultConverter;
use GridCP\Proxmox\Result\TaskResult;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class TaskResultTest extends TestCase
{
    public function testWithOkExitStatus(): void
    {
        $converter = new ResultConverter();

        $json = json_encode([
            'data' => [
                'starttime' => 1774456091,
                'upid' => 'UPID:nodeName:XXXX:XXXX:XXXX:resize:101:root@pam:',
                'status' => 'stopped',
                'pstart' => 44250116,
                'exitstatus' => 'OK',
                'pid' => 963294,
                'id' => '101',
                'node' => 'nodeName',
                'type' => 'resize',
                'user' => 'root@pam',
            ],
        ], JSON_THROW_ON_ERROR);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getReasonPhrase')->willReturn('OK');
        $response->method('getBody')->willReturn($stream);

        $result = $converter->convert($response, TaskResult::class);
        $this->assertInstanceOf(TaskResult::class, $result);
        $this->assertEquals('stopped', $result->status);
        $this->assertEquals('OK', $result->exitstatus);
    }

    public function testWithNonOkExitStatus(): void
    {
        $converter = new ResultConverter();

        $json = json_encode([
            'data' => [
                'starttime' => 1774456091,
                'upid' => 'UPID:nodeName:XXXX:XXXX:XXXX:resize:101:root@pam:',
                'status' => 'stopped',
                'pstart' => 44250116,
                'exitstatus' => 'shrinking disks is not supported',
                'pid' => 963294,
                'id' => '101',
                'node' => 'nodeName',
                'type' => 'resize',
                'user' => 'root@pam',
            ],
        ], JSON_THROW_ON_ERROR);

        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($json);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getReasonPhrase')->willReturn('OK');
        $response->method('getBody')->willReturn($stream);

        $result = $converter->convert($response, TaskResult::class);
        $this->assertInstanceOf(TaskResult::class, $result);
        $this->assertEquals('stopped', $result->status);
        $this->assertEquals('shrinking disks is not supported', $result->exitstatus);
    }
}
