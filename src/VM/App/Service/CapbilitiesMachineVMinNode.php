<?php

declare(strict_types=1);

namespace GridCP\Proxmox_Client\VM\App\Service;





use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\CapbilitiesMachineException;

class CapbilitiesMachineVMinNode extends GClientBase

{

    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)

    {

        parent::__construct($connection, $cookiesPVE);

    }



    public function __invoke(string $node)

    {
        
        try{


          $params = [
            'node' => $node,
          ];
          $result =  $this->Get("nodes/".$node."/capabilities/qemu/machines");

          return $result;

        }catch(\Exception $ex){

            if ($ex->getCode()===500) throw new CapbilitiesMachineException($ex->getMessage());

            throw new CapbilitiesMachineException("Error in Capabilities VM");



        }

        return null;

    }





}