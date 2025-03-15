<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Cpus\Domain\Exceptions;

class CpuNotFound extends  \Exception
{
    public function __construct()
    {
        parent::__construct("Cpu Not Found", 204);
    }

}