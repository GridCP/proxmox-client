<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\VM\App\Service;


use GridCP\Proxmox_Client\Commons\Application\Helpers\GFunctions;
use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\PostRequestException;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\VmErrorStop;

class StopVMinNode extends GClientBase
{
    use GFunctions;

    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)
    {
        parent::__construct($connection, $cookiesPVE);
    }

    public function __invoke(string  $node, int $vmid):string|PostRequestException|VmErrorStop|null{
        try {
            $body = [
                'vmid' => $vmid
            ];
            $result = $this->Post("nodes/" . $node . "/qemu/" . $vmid . "/status/stop", $body);
            return  $result->getBody()->getContents();
        }catch (PostRequestException $e ) {
            if ($e->getCode()===500) throw new VmErrorStop($e->getMessage());
            throw new VmErrorStop("Error in create VM");
        }

    }
}