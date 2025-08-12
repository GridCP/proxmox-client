<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Currrent\App\Service;

use GridCP\Proxmox_Client\Commons\Application\Helpers\GFunctions;
use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\AuthFailedException;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\HostUnreachableException;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\Currrent\Domain\Exceptions\CurrrentNotFound;
use GridCP\Proxmox_Client\Currrent\Domain\Reponses\CurrrentResponse;
use GuzzleHttp\Exception\GuzzleException;

final class GetCurrrentFromNode extends GClientBase
{
    use GFunctions;

     public function __construct(Connection $connection, CookiesPVE $cookiesPVE)
     {
         parent::__construct($connection, $cookiesPVE);
     }


    public function __invoke(string $node, string $vmid)
    {
        try {
            $result = $this->Get("nodes/" . $node . "/qemu/" . $vmid . "/status/current", []);
            if (empty($result)) throw new CurrrentNotFound();
            return new CurrrentResponse($result);
        }catch (GuzzleException $ex){
            if ($ex->getCode() === 401) throw new AuthFailedException();
            if ($ex->getCode() === 0) throw new HostUnreachableException();
        }
        return  null;

    }

}

