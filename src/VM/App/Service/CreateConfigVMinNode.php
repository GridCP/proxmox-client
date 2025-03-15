<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\VM\App\Service;


use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\PutRequestException;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\GetConfigVMException;

final class CreateConfigVMinNode extends  GClientBase
{
    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)
    {
        parent::__construct($connection, $cookiesPVE);
    }

    public function __invoke(string $node, int $vmid,?int $index, ?string $discard, ?string $cache, ?string $import):?string
    {
        try {
            $body = [
                'scsi'.$index => 'discard='.$discard,
              //  'scsi'.$index => 'file=local-lvm:vm-102-disk-0,size=32',
               // 'scsi'.$index =>'cache='.$cache,
               // 'scsi'.$index =>'import-from='.$import
            ];
            $result = $this->Post("nodes/".$node."/qemu/".$vmid."/config", $body);
            if(is_null($result)) return throw new GetConfigVMException("Error in config VM");
            $getContent = $result->getBody()->getContents();
            $getCode = $result->getStatusCode();
            if($getCode != 'CODE200') throw  new GetConfigVMException($getContent);
            return $getContent;
        }catch (PutRequestException $e ){
            if ($e->getCode()===500) throw new GetConfigVMException($e->getMessage());
            return throw new GetConfigVMException("Error in create VM");
        }
    }

}