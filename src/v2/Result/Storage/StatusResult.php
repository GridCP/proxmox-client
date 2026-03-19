<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result\Storage;

use GridCP\Proxmox\Result\ResultInterface;

final readonly class StatusResult implements ResultInterface
{
    public function __construct(
        public string $content,
        public string $type,
        public ?int $active = null,
        public ?int $avail = null,
        public ?int $enabled = null,
        public ?int $shared = null,
        public ?int $total = null,
        public ?int $used = null,
    ) {
    }
}
