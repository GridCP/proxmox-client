<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result;

class StopResult implements ResultInterface
{
    public function __construct(
        public ?string $upid,
    ) {
    }
}
