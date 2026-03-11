<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Tests\Result;

use GridCP\Proxmox\Exception\AuthenticationException;
use GridCP\Proxmox\Result\RebootResult;
use GridCP\Proxmox\Result\ResultConverter;
use GridCP\Proxmox\Result\ResumeResult;
use GridCP\Proxmox\Result\TaskResult;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResultConverterTest extends TestCase
{
    public function testConvertRebootResponseToObjectUsingDenormalizer(): void
    {
        $converter = new ResultConverter();
        $response = $this->createResponse(200, '{"data":"UPID:test"}');

        $result = $converter->convert($response, RebootResult::class);

        $this->assertInstanceOf(RebootResult::class, $result);
        $this->assertSame('UPID:test', $result->upid);
    }

    public function testConvertTaskResponseToObjectUsingDenormalizer(): void
    {
        $converter = new ResultConverter();
        $response = $this->createResponse(
            200,
            json_encode([
                'data' => [
                    'id' => '104',
                    'user' => 'root@pam',
                    'exitstatus' => 'OK',
                    'status' => 'stopped',
                    'pstart' => 1,
                    'starttime' => 2,
                    'type' => 'qmstart',
                    'upid' => 'UPID:test',
                    'pid' => 1234,
                    'node' => 'nodeA',
                ],
            ]),
        );

        $result = $converter->convert($response, TaskResult::class);

        $this->assertInstanceOf(TaskResult::class, $result);
        $this->assertSame('UPID:test', $result->upid);
        $this->assertSame(1234, $result->pid);
        $this->assertSame('nodeA', $result->node);
    }

    public function testConvertThrowsAuthenticationExceptionOn401(): void
    {
        $this->expectException(AuthenticationException::class);

        $converter = new ResultConverter();
        $response = $this->createResponse(401, '{"data":null,"message":"auth failed"}');

        $converter->convert($response, RebootResult::class);
    }

    public function testConvertThrowsRuntimeExceptionOn403PermissionCheckFailed(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Permission check failed (/vms/100, VM.PowerMgmt)');

        $converter = new ResultConverter();
        $response = $this->createResponse(
            403,
            json_encode([
                'message' => 'Permission check failed (/vms/100, VM.PowerMgmt)',
                'data' => null,
            ]),
        );

        $converter->convert($response, RebootResult::class);
    }

    public function testConvertThrowsRuntimeExceptionOn500VmNotRunning(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('VM 100 not running');

        $converter = new ResultConverter();
        $response = $this->createResponse(
            500,
            json_encode([
                'message' => 'VM 100 not running',
                'data' => null,
            ]),
        );

        $converter->convert($response, ResumeResult::class);
    }

    public function testConvertThrowsRuntimeExceptionOn595WithResponseMessage(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Network is unreachable');

        $converter = new ResultConverter();
        $response = $this->createResponse(
            595,
            json_encode([
                'message' => 'Network is unreachable',
                'data' => null,
            ]),
            'Network is unreachable',
        );

        $converter->convert($response, ResumeResult::class);
    }

    public function testConvertThrowsRuntimeExceptionOn595WithReasonPhraseWhenBodyIsEmpty(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Network is unreachable');

        $converter = new ResultConverter();
        $response = $this->createResponse(595, '', 'Network is unreachable');

        $converter->convert($response, ResumeResult::class);
    }

    private function createResponse(int $statusCode, string $payload, string $reasonPhrase = ''): ResponseInterface
    {
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($payload);

        $response->method('getStatusCode')->willReturn($statusCode);
        $response->method('getReasonPhrase')->willReturn($reasonPhrase);
        $response->method('getBody')->willReturn($stream);

        return $response;
    }
}
