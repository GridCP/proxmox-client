<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\VM\Domain\Responses;

class VmsResponse
{
    private readonly array $vms;


    public function __construct(VmResponse ...$vms){
        $this->vms =$vms;
    }

    public function vms():array{
        return $this->vms;
    }

}