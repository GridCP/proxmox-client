<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\VM\Domain\Exceptions;

final class VmErrorCreate extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Error create VM".$message, 400);
    }
}