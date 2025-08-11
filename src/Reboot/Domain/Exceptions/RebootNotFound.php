<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Reboot\Domain\Exceptions;

class RebootNotFound extends  \Exception
{
    public function __construct()
    {
        parent::__construct("Reboot Not Found", 204);
    }

}