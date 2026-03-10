<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use GridCP\Proxmox\Api\Parameters\RebootParameters;
use GridCP\Proxmox\Api\Parameters\StartParameters;
use GridCP\Proxmox\Api\Parameters\StopParameters;
use GridCP\Proxmox\Result\ResultInterface;

interface StatusApiInterface
{
    public function status(): ResultInterface;

    public function current(): ResultInterface;

    public function reboot(?RebootParameters $parameters = null): ResultInterface;

    public function reset(bool $skiplock = false): ResultInterface;

    public function resume(bool $nocheck = false, bool $skiplock = false): ResultInterface;

    public function shoutdown(
        bool $forceStop = false,
        bool $keepActive = false,
        bool $skiplock = false,
        ?int $timeout = null,
    ): ResultInterface;

    public function start(?StartParameters $parameters = null): ResultInterface;

    public function stop(?StopParameters $parameters = null): ResultInterface;

    public function suspend(): ResultInterface;
}
