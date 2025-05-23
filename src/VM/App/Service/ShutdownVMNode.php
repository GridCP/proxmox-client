<?php

declare(strict_types=1);

namespace GridCP\Proxmox_Client\VM\App\Service;



use GridCP\Proxmox_Client\Commons\Application\Helpers\GFunctions;
use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\PostRequestException;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\ShutdownException;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\VmErrorDestroy;

class ShutdownVMNode extends GClientBase

{

    use GFunctions;



    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)

    {

        parent::__construct($connection, $cookiesPVE);

    }


    /**
     * @param string $node
     * @param int $vmid
     * @return string|PostRequestException|VmErrorDestroy|null
     * @throws VmErrorDestroy
     * @throws PostRequestException
     * 
     */
    public function __invoke(string  $node, int $vmid)
    {

        try {

            $body = [
                
                'node' => $node,
                'vmid' => $vmid

            ];

            $result = $this->Post("nodes/" . $node . "/qemu/" . $vmid . "/status/shutdown", $body);
            return  $result->getBody()->getContents();

        }catch (PostRequestException $e ) {

            if ($e->getCode()===500) throw new ShutdownException($e->getMessage());

            throw new ShutdownException("Error in Shutdown VM");

        }

    }

}