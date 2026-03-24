<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result\Qemu;

use GridCP\Proxmox\Result\ResultInterface;

class ResizeResult implements ResultInterface
{
    public function __construct(
        public ?string $upid,
    ) {
    }
}
