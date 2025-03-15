<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Commons\Domain\Exceptions;

final class AuthFailedException extends \Exception
{

    public function __construct()
    {
        parent::__construct("Auth Failed", 401);
    }
}