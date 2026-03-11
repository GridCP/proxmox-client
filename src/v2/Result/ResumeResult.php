<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result;

final readonly class ResumeResult implements ResultInterface
{
    public function __construct(
        public ?string $upid,
    ) {
    }
}
