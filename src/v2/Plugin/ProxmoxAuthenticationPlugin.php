<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Plugin;

use GridCP\Proxmox\Api\AuthMethod;
use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;

readonly class ProxmoxAuthenticationPlugin implements Plugin
{
    public function __construct(
        private ?string $realm = null,
        private ?string $ticket = null,
        private ?string $CSRFPreventionToken = null,
        private ?string $username = null,
        private ?string $tokenId = null,
        private ?string $token = null,
        private AuthMethod $authMethod = AuthMethod::API_TOKEN,
    ) {
    }

    public function handleRequest(
        RequestInterface $request,
        callable $next,
        callable $first,
    ): Promise {
        if (AuthMethod::TICKET === $this->authMethod) {
            $request = $request
                ->withHeader('Cookie', sprintf('PVEAuthCookie=%s', $this->ticket))
                ->withHeader('CSRFPreventionToken', $this->CSRFPreventionToken);
        }

        if (AuthMethod::API_TOKEN === $this->authMethod) {
            $value = sprintf(
                'PVEAPIToken=%s@%s!%s=%s',
                $this->username,
                $this->realm,
                $this->tokenId,
                $this->token,
            );
            $request = $request->withHeader('Authorization', $value);
        }

        return $next($request);
    }
}
