<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Api;

use GridCP\Proxmox\Api\Result\ResultInterface;
use GridCP\Proxmox\Api\Result\ShoutdownResult;
use GridCP\Proxmox\Api\Result\SuspendResult;

interface StatusApiInterface
{
    public function status(): ResultInterface;

    public function current(): ResultInterface;

    public function reboot(?int $timeout = null): ResultInterface;

    public function reset();

    public function resume();

    public function shoutdown(): ShoutdownResult;

    public function start();

    public function stop();

    public function suspend(): SuspendResult;
}
