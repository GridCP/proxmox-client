<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\VM\Domain\Exceptions;

final class ResizeVMDiskException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Error Resize Disk->".$message, 400);
    }
}