<?php

declare(strict_types=1);

namespace GridCP\Proxmox_Client\VM\Domain\Exceptions;



final class MoveDiskVMException extends \Exception

{

    public function __construct(string $message)

    {

        parent::__construct("Error Move Disk -> ".$message, 409);

    }

}