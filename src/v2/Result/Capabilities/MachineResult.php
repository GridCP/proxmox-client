<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result\Capabilities;

use GridCP\Proxmox\Result\ResultInterface;

final readonly class MachineResult implements ResultInterface
{
    public function __construct(
        public string $id,
        public string $type,
        public string $version,
        public ?string $changes = null,
    ) {
    }
}
