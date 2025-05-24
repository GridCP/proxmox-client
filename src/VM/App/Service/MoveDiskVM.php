<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\VM\App\Service;

use Exception;
use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\PutRequestException;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\MoveDiskVMException;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\ResizeVMDiskException;


final class MoveDiskVM extends GClientBase
{
    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)
    {
        parent::__construct($connection, $cookiesPVE);
    }

    public function __invoke(string $node, int $vmid, string $disk, string $storage):?string
    {

        try {
            $body = [
                'disk' => $disk,
                'storage' => $storage,
            ];
            $result = $this->Post("nodes/" . $node . "/qemu/" . $vmid . "/move_disk", $body);
            return $result->getBody()->getContents();
        }catch (Exception $ex){
            return throw new MoveDiskVMException("Error in Move Disk VM");
        }
    }
}