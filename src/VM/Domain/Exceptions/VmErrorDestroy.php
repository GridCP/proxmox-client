<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\VM\Domain\Exceptions;

final class VmErrorDestroy extends  \Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Error destroy VM".$message, 400);
    }
}