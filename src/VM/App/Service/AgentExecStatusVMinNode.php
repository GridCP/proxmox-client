<?php

declare(strict_types=1);

namespace GridCP\Proxmox_Client\VM\App\Service;



use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\AgentExecStatusVMException;

class AgentExecStatusVMinNode extends GClientBase

{

    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)

    {

        parent::__construct($connection, $cookiesPVE);

    }



    public function __invoke(string $node, int $vmid, string $pid)

    {
        
        try{


          $params = [
            'node' => $node,
            'pid' => $pid,
            'vmid' => $vmid
          ];
          $result =  $this->Get("nodes/".$node."/qemu/".$vmid."/agent/exec-status", $params);
          return $result;

        }catch(\Exception $ex){ 
            if ($ex->getCode()===500) throw new AgentExecStatusVMException($ex->getMessage());
            return throw new AgentExecStatusVMException("Error in Agent Status VM");

        }

        return null;

    }





}