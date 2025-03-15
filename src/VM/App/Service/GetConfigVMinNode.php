<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\VM\App\Service;


use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;

class GetConfigVMinNode extends GClientBase
{
    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)
    {
        parent::__construct($connection, $cookiesPVE);
    }

    public function __invoke(string $node, int $vmid):?array
    {
        try{

          $result =  $this->Get("nodes/".$node."/qemu/".$vmid."/config");
          return $result;
        }catch(\Exception $ex){

        }
        return null;
    }


}