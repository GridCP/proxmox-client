<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Cluster\Domain\Exceptions;

class ClusterNotFound Extends \Exception
{
    public function __construct()
    {
        parent::__construct("Cluster Not Found", 204);
    }

}