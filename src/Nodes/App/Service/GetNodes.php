<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Nodes\App\Service;

use GridCP\Proxmox_Client\Commons\Application\Helpers\GFunctions;
use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\AuthFailedException;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\HostUnreachableException;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\Nodes\Domain\Responses\NodeResponse;
use GridCP\Proxmox_Client\Nodes\Domain\Responses\NodesResponse;
use GuzzleHttp\Exception\GuzzleException;


final class GetNodes extends GClientBase
{
    use GFunctions;



    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)
    {
        parent::__construct($connection, $cookiesPVE);
    }


    public function __invoke():?NodesResponse
    {
        try {
            $result = $this->Get("nodes", []);
            return new NodesResponse(...array_map($this->toResponse(), $result));
        }catch(GuzzleException $ex){
            if ($ex->getCode() === 401) throw new AuthFailedException();
            if ($ex->getCode() === 0) throw new HostUnreachableException();
        }
        return  null;
    }

    public function toResponse():callable
    {
        return static fn($result): NodeResponse => new NodeResponse(
            $result['status'],
            $result['level'],
            $result['id'],
            $result['ssl_fingerprint'],
            $result['maxmem'],
            $result['disk'],
            $result['uptime'],
            $result['mem'],
            $result['node'],
            $result['cpu'],
            $result['maxcpu'],
            $result['type'],
            $result['maxdisk'],
        );
    }
}