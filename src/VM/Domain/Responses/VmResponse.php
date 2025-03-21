<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\VM\Domain\Responses;

 class VmResponse
{

    public function __construct(private string $data)
    {
    }

    public function getData():string{
        return $this->data;
    }
}