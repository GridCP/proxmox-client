<?php

declare(strict_types=1);

namespace GridCP\Proxmox_Client\Commons\Domain\Exceptions;



final class NotFoundSOException  extends  \Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Not Found SO ->".$message, 401);
    }
}