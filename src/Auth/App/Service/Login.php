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

    public function __invoke(): LoginResponse|AuthFailedException|HostUnreachableException|null

    {
        try {
            $body=[
                'username' => $this->connection->getUsername(),
                'password' => $this->connection->getPassword(),
                'realm' => $this->connection->getRealm()
            ];
            $result=  $this->client->request("POST", $this->connection->getUri() .'access/ticket' , [
                'https_errors'=>false,
                'verify' => false,
                'headers' => $this->defaultHeaders,
                'json' => (count($body) > 0 ) ? $body : null]);
           $response = $this->decodeBody($result);
           if($result->getStatusCode()===401) throw new AuthFailedException();
           $cookie = $this->getCookies($response['ticket'], $this->connection->getHost());
           return new LoginResponse($response['CSRFPreventionToken'], $cookie, $response['ticket']);
        } catch (GuzzleException $ex) {
            if ($ex->getCode() === 401) throw new AuthFailedException();
            if ($ex->getCode() === 0) throw new HostUnreachableException();
        }
    }


}
