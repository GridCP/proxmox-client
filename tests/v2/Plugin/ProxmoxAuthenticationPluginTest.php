<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Tests\Plugin;

use GridCP\Proxmox\Api\AuthMethod;
use GridCP\Proxmox\Api\Plugin\ProxmoxAuthenticationPlugin;
use Http\Promise\FulfilledPromise;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

class ProxmoxAuthenticationPluginTest extends TestCase
{
    public function testHandleRequestWithTicketAddsCookieAndCsrfHeaders(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $matcher = $this->exactly(2);
        $request->expects($matcher)
            ->method('withHeader')
            ->willReturnCallback(function (string $name, string $value) use ($matcher, $request) {
                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertSame(['Cookie', 'PVEAuthCookie=ticket'], [$name, $value]),
                    2 => $this->assertSame(['CSRFPreventionToken', 'CSRFPreventionToken'], [$name, $value]),
                };

                return $request;
            });

        $plugin = new ProxmoxAuthenticationPlugin(
            'realm',
            'ticket',
            'CSRFPreventionToken',
            null,
            null,
            null,
            AuthMethod::TICKET,
        );

        $plugin->handleRequest(
            $request,
            fn () => new FulfilledPromise('promise-result'),
            fn () => throw new \Exception('Should not be called'),
        );
    }

    public function testHandleRequestWithApiTokenAddsAuthorizationHeader(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $request->expects($this->once())
            ->method('withHeader')
            ->with(
                'Authorization',
                'PVEAPIToken=username@realm!tokenId=token',
            )
            ->willReturn($request);

        $plugin = new ProxmoxAuthenticationPlugin(
            'realm',
            null,
            null,
            'username',
            'tokenId',
            'token',
            AuthMethod::API_TOKEN,
        );

        $plugin->handleRequest(
            $request,
            fn () => new FulfilledPromise('promise-result'),
            fn () => throw new \Exception('Should not be called'),
        );
    }
}
