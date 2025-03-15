<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Commons\Domain\Exceptions;

final class PostRequestException  extends  \Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Post failed ->".$message, 401);
    }
}