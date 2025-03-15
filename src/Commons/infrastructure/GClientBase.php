<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Commons\infrastructure;

use GridCP\Proxmox_Client\Commons\Application\Helpers\GFunctions;
use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\AuthFailedException;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\DeleteRequestException;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\GetRequestException;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\HostUnreachableException;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\PostRequestException;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\PutRequestException;
use GridCP\Proxmox_Client\Commons\Domain\Models\CoockiesPVE;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

abstract class GClientBase
{

    use GFunctions;
    private Client $client;
    private ?Connection $connection;
    private ?CoockiesPVE $cookies;

    private array $defaultHeaders = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];
    public function __construct(?Connection $connection, ?CoockiesPVE $cookies)
    {
        $this->cookies= $cookies;
        $this->connection = $connection;
        $this->client = new Client([$connection->getHost()]);
    }
    protected function Get(string $request, array $params=[]):?array
    {
       try{
           if ($this->cookies->getCookies() === null) throw new AuthFailedException();
           $result= $this->client->request('GET', $this->connection->getUri().$request,[
              'https_errors' => false,
              'verify'=> false,
               'headers'=>array_merge($this->defaultHeaders,['CSRFPreventionToken'=>$this->cookies->getCSRFPreventionToken()]),
               'query' =>$params,
               'exceptions'=>false,
               'cookies'=>$this->cookies->getCookies(),
           ]);
           if ($result->getStatusCode()==401) throw new AuthFailedException();
           if ($result->getStatusCode() === 0) throw new HostUnreachableException();
           return $this->decodeBody($result);
       }catch (GuzzleException $ex){
           if ($ex->getCode() === 0) throw new HostUnreachableException();
           if ($ex->getCode() === 401) throw new AuthFailedException();
           throw new GetRequestException($ex->getMessage());
       }
    }

    protected function Post(string $request, array $requestBody): ?ResponseInterface
    {
       try {
            $result=  $this->client->request("POST", $this->connection->getUri() .  $request, [
                'https_errors'=>false,
                'verify' => false,
                'headers' => array_merge($this->defaultHeaders,['CSRFPreventionToken'=>$this->cookies->getCSRFPreventionToken()]),
                'cookies'=>$this->cookies->getCookies(),
                'exceptions'=>false,
                'json' => (count($requestBody) > 0 ) ? $requestBody : null,
            ]);
           if ($result->getStatusCode()==401) throw new AuthFailedException();
           if ($result->getStatusCode() === 0) throw new HostUnreachableException();
           return $result;
        }catch (GuzzleException $ex){
           if ($ex->getCode() === 0) throw new HostUnreachableException();
           if ($ex->getCode() === 401) throw new AuthFailedException();
           throw new PostRequestException($ex->getMessage());
        }
    }

    protected  function Put(string $request, array $requestBody):?ResponseInterface
    {

        try {
            $result = $this->client->request("PUT", $this->connection->getUri() .  $request, [
                'https_errors'=>false,
                'verify' => false,
                'headers' => array_merge($this->defaultHeaders,['CSRFPreventionToken'=>$this->cookies->getCSRFPreventionToken()]),
                'cookies'=>$this->cookies->getCookies(),
                'exceptions'=>false,
                'json' => (count($requestBody) > 0 ) ? $requestBody : null,
            ]);
            if ($result->getStatusCode()==401) throw new AuthFailedException();
            if ($result->getStatusCode() === 0) throw new HostUnreachableException();
            return $result;
        }catch (GuzzleException $ex){
            if ($ex->getCode() === 0) throw new HostUnreachableException();
            if ($ex->getCode() === 401) throw new AuthFailedException();
            throw new PutRequestException($ex->getMessage());
        }
    }

    protected function Delete(string $request, array $params=[]):?ResponseInterface
    {
        try{
            if ($this->cookies->getCookies() === null) throw new AuthFailedException();
            $result= $this->client->request('DELETE', $this->connection->getUri().$request,[
                'https_errors' => false,
                'verify'=> false,
                'headers'=>array_merge($this->defaultHeaders,['CSRFPreventionToken'=>$this->cookies->getCSRFPreventionToken()]),
                'query' =>$params,
                'exceptions'=>false,
                'cookies'=>$this->cookies->getCookies(),
            ]);
            if ($result->getStatusCode()==401) throw new AuthFailedException();
            if ($result->getStatusCode() === 0) throw new HostUnreachableException();
            return $result;
        }catch (GuzzleException $ex){
            if ($ex->getCode() === 0) throw new HostUnreachableException();
            if ($ex->getCode() === 401) throw new AuthFailedException();
            throw new DeleteRequestException($ex->getMessage());
        }
    }

    protected function getClient():Client{
        return $this->client;
    }
    public function getConnection():Connection{
        return $this->connection;
    }

}