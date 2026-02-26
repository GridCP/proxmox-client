<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\UriTemplateHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class ProxmoxClientFactory implements ProxmoxClientFactoryInterface
{
    public function __construct(
        private ?HttpClientInterface $httpClient = null,
        private bool $verifyPeer = false,
        private bool $verifyHost = false,
        private float $timeout = 15.0,
    ) {
    }

    public function create(
        string $realm,
        string $username,
        string $password,
        string $host,
        ?int $port = null,
    ): ProxmoxApiClient {
        $httpClient = $this->buildClient($host, $port ?? 8006);

        $client = new ProxmoxApiClient($httpClient);

        return $client->login($realm, $username, $password);
    }

    public function createWithToken(string $token, string $host, ?int $port = null): ProxmoxApiClient
    {
        $httpClient = $this->buildClient($host, $port ?? 8006, [
            'headers' => [
                'Authorization' => 'PVEAPIToken='.$token,
            ],
        ]);

        return new ProxmoxApiClient($httpClient);
    }

    private function buildClient(string $host, int $port, array $extraOptions = []): HttpClientInterface
    {
        $baseUri = sprintf('https://%s:%d', $host, $port);
        $options = array_replace_recursive([
            'base_uri' => $baseUri,
            'verify_peer' => $this->verifyPeer,
            'verify_host' => $this->verifyHost,
            'timeout' => $this->timeout,
        ], $extraOptions);

        $httpClient = $this->httpClient ?? HttpClient::create($options);
        if ($this->httpClient instanceof HttpClientInterface) {
            $httpClient = $this->httpClient->withOptions($options);
        }

        return new UriTemplateHttpClient($httpClient);
    }
}
