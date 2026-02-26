<?php

namespace GridCP\Proxmox\Api;

class QemuApi
{
    public function __construct(
        private readonly ProxmoxApiClient $client,
        private readonly string $node,
        private readonly int $vmid,
    ) {
    }

    public function status(): StatusApi
    {
        return new StatusApi($this->client, $this->node, $this->vmid);
    }
}
