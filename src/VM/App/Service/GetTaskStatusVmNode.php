<?php

declare(strict_types=1);

namespace GridCP\Proxmox_Client\VM\App\Service;



use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\GetTaskStatusVMException;

class GetTaskStatusVmNode extends GClientBase

{

    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)

    {

        parent::__construct($connection, $cookiesPVE);

    }



    public function __invoke(string $node, string $upid)

    {
        
        try{
            
          $result =  $this->Get("nodes/".$node."/tasks/".$upid."/status");

          return $result;

        }catch(\Exception $ex){
            if ($ex->getCode()===500) throw new GetTaskStatusVMException($ex->getMessage());
            return throw new GetTaskStatusVMException("Error in Task Status VM");
        }

        return null;

    }





}