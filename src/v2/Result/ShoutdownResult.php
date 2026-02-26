<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Result;

class ShoutdownResult implements ResultInterface
{
    public function __construct(
        public ?string $upid,
    ) {
    }

    /**
     * @param array{data: string} $result
     */
    public static function fromArray(array $result): self
    {
        return new self(
            $result['data'] ?? null,
        );
    }
}
