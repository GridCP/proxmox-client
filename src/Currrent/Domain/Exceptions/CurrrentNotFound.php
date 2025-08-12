<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Currrent\Domain\Exceptions;

class CurrrentNotFound extends  \Exception
{
    public function __construct()
    {
        parent::__construct("Currrent Not Found", 204);
    }

}