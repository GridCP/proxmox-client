<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Result;

interface RawResultInterface
{
    public function getData(): array;

    public function getObject(): object;
}
