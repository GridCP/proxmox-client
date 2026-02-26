<?php

declare(strict_types=1);

namespace GridCP\Proxmox_Client\Auth\App\Service;

use GridCP\Proxmox_Client\Auth\Domain\Responses\LoginResponse;
use GridCP\Proxmox_Client\Commons\Application\Helpers\GFunctions;
use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\AuthFailedException;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\HostUnreachableException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

final class Login
{
    use GFunctions;

    private string $ticket;

    private Connection $connection;
    private Client $client;

    private array $defaultHeaders = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->client = new Client([$connection->getHost()]);
    }

    /**
     * @throws AuthFailedException
     * @throws HostUnreachableException
     */
    public function __invoke(): LoginResponse
    {
        try {
            $result = $this->client->request(
                'POST',
                $this->connection->getUri().'access/ticket',
                $this->buildRequestOptions(),
            );

            if (401 === $result->getStatusCode()) {
                throw new AuthFailedException();
            }

            $response = $this->decodeBody($result);
            $cookie = $this->getCookies($response['ticket'], $this->connection->getHost());

            return new LoginResponse($response['CSRFPreventionToken'], $cookie, $response['ticket']);
        } catch (GuzzleException $ex) {
            if (401 === $ex->getCode()) {
                throw new AuthFailedException($ex->getMessage());
            }
            if (0 === $ex->getCode()) {
                throw new HostUnreachableException();
            }

            throw $ex;
        }
    }

    /**
     * @return array{
     *     https_errors: bool,
     *     verify: bool,
     *     headers: array{Content-Type: string, Accept: string},
     *     json: array<array{username: string, password: string, realm: string}>,
     * }
     */
    public function buildRequestOptions(): array
    {
        return [
            'https_errors' => false,
            'verify' => false,
            'headers' => $this->defaultHeaders,
            'json' => [
                'username' => $this->connection->getUsername(),
                'password' => $this->connection->getPassword(),
                'realm' => $this->connection->getRealm(),
            ],
        ];
    }
}
