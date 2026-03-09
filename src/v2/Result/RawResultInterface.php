<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result;

interface RawResultInterface
{
    public function getData(): array;

    public function getObject(): object;
}
