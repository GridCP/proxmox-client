<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Proxmox\Version\Domain\Exceptions;

final class VersionError extends \Exception
{
    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct("Error in obtain version ->".$message, 400);
    }
}