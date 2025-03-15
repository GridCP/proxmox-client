<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Commons\Domain\Exceptions;

final class DeleteRequestException extends  \Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Delete failed ->".$message, 401);
    }
}