<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Api;

use GridCP\Proxmox\Api\Result\ResultInterface;
use GridCP\Proxmox\Api\Result\SuspendResult;

interface StatusApiInterface
{
    public function status(): ResultInterface;

    public function current(): ResultInterface;

    public function reboot(?int $timeout = null): ResultInterface;

    public function reset(bool $skiplock = false): ResultInterface;

    public function resume(bool $nocheck = false, bool $skiplock = false): ResultInterface;

    public function shoutdown(
        bool $forceStop = false,
        bool $keepActive = false,
        bool $skiplock = false,
        bool $timeout = false,
    ): ResultInterface;

    public function start();

    public function stop();

    public function suspend(): SuspendResult;
}
