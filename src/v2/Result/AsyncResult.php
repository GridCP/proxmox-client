<?php

namespace GridCP\Proxmox\Result;

class AsyncResult implements ResultInterface
{
    public function __construct(
        public ?string $upid,
    ) {
    }
}
