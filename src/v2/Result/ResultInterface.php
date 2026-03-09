<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result;

interface ResultInterface
{
    public static function fromArray(array $result): self;
}
