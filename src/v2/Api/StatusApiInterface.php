<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use GridCP\Proxmox\Api\Parameters\RebootParameters;
use GridCP\Proxmox\Api\Parameters\ResetParameters;
use GridCP\Proxmox\Api\Parameters\ResumeParameters;
use GridCP\Proxmox\Api\Parameters\ShutdownParameters;
use GridCP\Proxmox\Api\Parameters\StartParameters;
use GridCP\Proxmox\Api\Parameters\StopParameters;
use GridCP\Proxmox\Api\Parameters\SuspendParameters;
use GridCP\Proxmox\Result\ResultInterface;

interface StatusApiInterface
{
    public function status(): ResultInterface;

    public function current(): ResultInterface;

    public function reboot(?RebootParameters $parameters = null): ResultInterface;

    public function reset(?ResetParameters $parameters = null): ResultInterface;

    public function resume(?ResumeParameters $parameters = null): ResultInterface;

    public function shutdown(?ShutdownParameters $parameters = null): ResultInterface;

    public function start(?StartParameters $parameters = null): ResultInterface;

    public function stop(?StopParameters $parameters = null): ResultInterface;

    public function suspend(?SuspendParameters $parameters = null): ResultInterface;
}
