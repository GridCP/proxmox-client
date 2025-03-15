<?php

declare(strict_types=1);

namespace GridCP\Proxmox_Client\VM\App\Service;





use GridCP\Proxmox_Client\Commons\Application\Helpers\GFunctions;
use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\PostRequestException;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\VmErrorReset;

class ResetVMNode extends GClientBase

{

    use GFunctions;



    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)

    {

        parent::__construct($connection, $cookiesPVE);

    }


    /**
     * @param string $node
     * @param int $vmid
     * @return string|PostRequestException|VmErrorReset|null
     * @throws VmErrorReset
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

            $result = $this->Post("nodes/" . $node . "/qemu/" . $vmid . "/status/reset", $body);
            return  $result->getBody()->getContents();

        }catch (PostRequestException $e ) {

            if ($e->getCode()===500) throw new VmErrorReset($e->getMessage());

            throw new VmErrorReset("Error in reset VM");

        }

    }

}