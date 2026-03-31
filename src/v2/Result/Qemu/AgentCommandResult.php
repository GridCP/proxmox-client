<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result\Qemu;

abstract class AgentCommandResult
{
    public function __construct(
        public ?int $pid,
    ) {
    }
}
