<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result\Capabilities;

use GridCP\Proxmox\Result\ResultInterface;

final readonly class CpuResult implements ResultInterface
{
    public function __construct(
        public string $vendor,
        public string $name,
        public bool $custom = false,
    ) {
    }

    public function isCustom(): bool
    {
        return true === $this->custom;
    }
}
