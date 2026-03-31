<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result\Result\Qemu;

use GridCP\Proxmox\Result\Result\ResultInterface;

final readonly class CreateVirtualMachineResult implements ResultInterface
{
    public function __construct(
        public string $upid,
    ) {
    }
}
