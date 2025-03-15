<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Networks\App\Service;

use GridCP\Proxmox_Client\Commons\Application\Helpers\GFunctions;
use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\AuthFailedException;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\HostUnreachableException;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\Networks\Domain\Exceptions\NetworksNotFound;
use GridCP\Proxmox_Client\Networks\Domain\Responses\NetworkResponse;
use GridCP\Proxmox_Client\Networks\Domain\Responses\NetworksResponse;
use GuzzleHttp\Exception\GuzzleException;


final class GetNetworksFromNode extends GClientBase
{
    use GFunctions;


    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)
    {
        parent::__construct($connection, $cookiesPVE);
    }

    public function __invoke(string $node):?NetworksResponse
    {
        try {
            $result = $this->Get("nodes/" . $node . "/network", []);
            if (empty($result)) throw new NetworksNotFound();
            return new NetworksResponse(...array_map($this->toResponse(), $result));
        }catch (GuzzleException $ex){
            if ($ex->getCode() === 401) throw new AuthFailedException();
            if ($ex->getCode() === 0) throw new HostUnreachableException();
        }
        return  null;
    }

    public function toResponse():callable
    {
        return static fn($result): NetworkResponse=>new NetworkResponse(
            (array_key_exists('method', $result))?$result['method']:null,
            (array_key_exists('bridge_fd', $result))?$result['bridge_fd']:"",
            array_key_exists('active', $result) && $result['active'] === 1,
            (array_key_exists('iface', $result))?$result['iface']:null,
            (array_key_exists('priority', $result))?$result['priority']:0,
            (array_key_exists('type',$result))?$result['type']:null,
            (array_key_exists('autostart', $result)) && $result['autostart'] === 1,
            (array_key_exists('method6', $result))?$result['method6']:"",
            (array_key_exists('bridge_stp', $result))?$result['bridge_stp']:"",
            (array_key_exists('netmask', $result))?$result['netmask']:"",
            (array_key_exists('cidr', $result))?$result['cidr']:"",
            (array_key_exists('bridge_ports', $result))?$result['bridge_ports']:"",
            (array_key_exists('gateway', $result))?$result['gateway']:"",
            (array_key_exists('families', $result))? $result['families']:[],
            (array_key_exists('address', $result))?$result['address']:""
        );
    }

}