<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Networks\Domain\Exceptions;

class NetworksNotFound extends \Exception
{
    public function __construct()
    {
        parent::__construct("Networks Not Found", 204);
    }
}