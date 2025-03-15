<?php
namespace GridCP\Proxmox_Client\VM\Domain\IService;



interface IBuildVMData {

    public function buildData(): array;

}