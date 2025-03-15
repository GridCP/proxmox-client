<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Cpus\App\Service;

use GridCP\Proxmox_Client\Commons\Application\Helpers\GFunctions;
use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\AuthFailedException;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\HostUnreachableException;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\Cpus\Domain\Exceptions\CpuNotFound;
use GridCP\Proxmox_Client\Cpus\Domain\Reponses\CpuResponse;
use GridCP\Proxmox_Client\Cpus\Domain\Reponses\CpusResponse;
use GuzzleHttp\Exception\GuzzleException;

final class GetCpuFromNode extends GClientBase
{
    use GFunctions;

     public function __construct(Connection $connection, CookiesPVE $cookiesPVE)
     {
         parent::__construct($connection, $cookiesPVE);
     }


    public function __invoke(string $node):?CpusResponse
    {
        try {
            $result = $this->Get("nodes/" . $node . "/capabilities/qemu/cpu", []);
            if (empty($result)) throw new CpuNotFound();
            return new CpusResponse(...array_map($this->toResponse(), $result));
        }catch (GuzzleException $ex){
            if ($ex->getCode() === 401) throw new AuthFailedException();
            if ($ex->getCode() === 0) throw new HostUnreachableException();
        }
        return  null;
    }
    public function toResponse():callable
    {
        return static fn($result): CpuResponse=>new CpuResponse(
            (array_key_exists('vendor', $result))?$result['vendor']:"",
            (array_key_exists('name', $result))?$result['name']:"",
            array_key_exists('custom', $result) ?$result['custom']:0
        );
    }

}

