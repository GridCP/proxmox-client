<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result;

final readonly class ResetResult implements ResultInterface
{
    public function __construct(
        public ?string $upid,
    ) {
    }
}
