<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Reboot\App\Service;

use GridCP\Proxmox_Client\Commons\Application\Helpers\GFunctions;
use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\AuthFailedException;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\HostUnreachableException;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\Reboot\Domain\Exceptions\RebootNotFound;
use GridCP\Proxmox_Client\Reboot\Domain\Reponses\RebootResponse;
use GuzzleHttp\Exception\GuzzleException;

final class GetRebootFromNode extends GClientBase
{
    use GFunctions;

     public function __construct(Connection $connection, CookiesPVE $cookiesPVE)
     {
         parent::__construct($connection, $cookiesPVE);
     }


    public function __invoke(string $node, string $vmid)
    {
        try {
            $body = [
                'timeout' => 10
            ];
            $result = $this->Post("nodes/" . $node . "/qemu/" . $vmid . "/status/reboot", $body);
            if (empty($result)) throw new RebootNotFound();
            return $result->getReasonPhrase();
        }catch (GuzzleException $ex){
            if ($ex->getCode() === 401) throw new AuthFailedException();
            if ($ex->getCode() === 0) throw new HostUnreachableException();
        }
        return  null;

    }

}

