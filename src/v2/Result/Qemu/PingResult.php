<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result\Qemu;

use GridCP\Proxmox\Result\ResultInterface;

class PingResult implements ResultInterface
{
    public function __construct(
        public readonly ?array $result,
    ) {
    }

    public function isOk(): bool
    {
        return null !== $this->result;
    }
}
