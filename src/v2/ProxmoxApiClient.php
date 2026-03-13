<?php

declare(strict_types=1);

namespace GridCP\Proxmox;

use GridCP\Proxmox\Api\NodeApi;
use GridCP\Proxmox\Plugin\ProxmoxAuthenticationPlugin;
use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin\LoggerPlugin;
use Monolog\Logger;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ProxmoxApiClient /* implements HttpMethodsClientInterface */
{
    private readonly ProxmoxApiClientBuilder $httpClientBuilder;

    public function __construct(
        ?ProxmoxApiClientBuilder $httpClientBuilder = null,
    ) {
        $this->httpClientBuilder = $builder = $httpClientBuilder ?? new ProxmoxApiClientBuilder();

        $builder->addPlugin(new LoggerPlugin(new Logger('proxmox-api-client')));
    }

    public static function createWithHttpClient(ClientInterface $httpClient): self
    {
        return new self(new ProxmoxApiClientBuilder($httpClient));
    }

    /**
     * @throws ClientExceptionInterface
     */
    private function login(
        string $realm,
        string $username,
        #[\SensitiveParameter]
        string $password,
    ): array {
        $response = $this->post('/api2/json/access/ticket', [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ], json_encode([
            'username' => $username,
            'password' => $password,
            'realm' => $realm,
        ]));

        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->__toString();
        if (200 !== $statusCode) {
            $msg = sprintf(
                'Failed to authenticate with Proxmox API: %s Response body: %s',
                $response->getReasonPhrase(),
                $body,
            );

            throw new \RuntimeException($msg);
        }

        if (true === str_starts_with($response->getHeaderLine('Content-Type'), 'application/json')) {
            /**
             * @var array{
             *     data: array{
             *         ticket: string,
             *         CSRFPreventionToken: string,
             *         username: string
             *     }
             * } $data
             */
            $content = json_decode($body, true);

            if (JSON_ERROR_NONE === json_last_error()) {
                return [
                    $content['data']['ticket'],
                    $content['data']['CSRFPreventionToken'],
                ];
            }
        }

        throw new \RuntimeException('Failed to authenticate with Proxmox API: Invalid response format');
    }

    public function authenticate(
        string $realm,
        string $username,
        ?string $password = null,
        ?string $tokenId = null,
        ?string $token = null,
        AuthMethod $authMethod = AuthMethod::API_TOKEN,
    ) {
        $this->getHttpClientBuilder()->removePlugin(ProxmoxAuthenticationPlugin::class);

        if (AuthMethod::TICKET === $authMethod) {
            if (null === $password) {
                throw new \InvalidArgumentException('Password is required for TICKET authentication method');
            }

            list($ticket, $csrfToken) = $this->login($realm, $username, $password);
            $authenticationPlugin = new ProxmoxAuthenticationPlugin(
                realm: $realm,
                ticket: $ticket,
                CSRFPreventionToken: $csrfToken,
                authMethod: $authMethod,
            );
        }

        if (AuthMethod::API_TOKEN === $authMethod) {
            if (null === $tokenId || null === $token) {
                throw new \InvalidArgumentException('TokenId and token are required for API_TOKEN authentication method');
            }

            $authenticationPlugin = new ProxmoxAuthenticationPlugin(
                realm: $realm,
                username: $username,
                tokenId: $tokenId,
                token: $token,
                authMethod: $authMethod,
            );
        }

        $this->getHttpClientBuilder()->addPlugin($authenticationPlugin);
    }

    public function get(string $url, array $headers = []): ResponseInterface
    {
        return $this->getHttpClient()->get($url, $headers);
    }

    public function post(string $uri, array $headers = [], string|StreamInterface|null $body = null): ResponseInterface
    {
        return $this->getHttpClient()->post($uri, $headers, $body);
    }

    public function delete(string $url, array $headers = [], string|StreamInterface|null $body = null): ResponseInterface
    {
        return $this->getHttpClient()->delete($url, $headers, $body);
    }

    private function getHttpClient(): HttpMethodsClient
    {
        return $this->httpClientBuilder->getHttpClient();
    }

    public function getHttpClientBuilder(): ProxmoxApiClientBuilder
    {
        return $this->httpClientBuilder;
    }

    public function nodes(?string $node = null): NodeApi
    {
        return new NodeApi($this, $node);
    }
}
