<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result;

class StatusResult implements ResultInterface
{
    public function __construct(
        public readonly string $subdir,
    ) {
    }
}
