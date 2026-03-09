<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result;

class StatusResult implements ResultInterface
{
    public function __construct(
        public readonly array $data,
    ) {
    }

    /**
     * @param array{data: array} $result
     */
    public static function fromArray(array $result): self
    {
        return new self($result['data'] ?? []);
    }
}
