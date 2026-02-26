<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use GridCP\Proxmox\Api\Result\ShoutdownResult;
use GridCP\Proxmox\Api\Result\SuspendResult;

interface StatusApiInterface
{
    public function shoutdown(): ShoutdownResult;

    public function suspend(): SuspendResult;
}
