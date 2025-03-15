<?php

declare(strict_types=1);

namespace GridCP\Proxmox_Client\VM\App\Service;



use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\AgentExecVMException;

class SetAgentExecVMinNode extends GClientBase
{

    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)
    {
        parent::__construct($connection, $cookiesPVE);
    }

    public function __invoke(string $node, int $vmid, array $commands)
    {
        
        try{
          $result =  $this->Post("nodes/".$node."/qemu/".$vmid."/agent/exec", $commands);
          $responseBody = $result->getBody()->getContents();
          $responseArray = json_decode($responseBody, true);

          return $responseBody;

        }catch(\Exception $ex){
            
            if ($ex->getCode()===500) throw new AgentExecVMException($ex->getMessage());
            return throw new AgentExecVMException("Error in Agent Exec.");
        }

        return null;

    }

}