<?php

declare(strict_types=1);

namespace GridCP\Proxmox_Client\VM\App\Service;





use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\AgentFileWriteVMException;

class SetAgentFileWriteVMinNode extends GClientBase

{

    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)

    {

        parent::__construct($connection, $cookiesPVE);

    }



    public function __invoke(string $node, int $vmid, array $commands)

    {
        
        try{


            $fileWrite = [
                        'node' => $node,
                        'vmid' => $vmid,
                        'content' => $commands['content'],
                        'file' => $commands['file']

                    ];

          $result =  $this->Post("nodes/".$node."/qemu/".$vmid."/agent/file-write", $fileWrite);
          $responseBody = $result->getBody()->getContents();          
          $responseArray = json_decode($responseBody, true);
             
          return $responseArray;

        }catch(\Exception $ex){            
            if ($ex->getCode()===500) throw new AgentFileWriteVMException($ex->getMessage());
            return throw new AgentFileWriteVMException("Error in Agent File Write VM");



        }

        return null;

    }





}