<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Commons\Domain\Exceptions;

final class GetRequestException  extends  \Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Get failed ->".$message, 401);
    }
}