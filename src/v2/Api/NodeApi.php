<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use GridCP\Proxmox\ProxmoxApiClient;

class NodeApi
{
    public function __construct(
        private readonly ProxmoxApiClient $client,
        private readonly string $node,
    ) {
    }

    public function qemu(int $vmid): QemuApi
    {
        return new QemuApi($this->client, $this->node, $vmid);
    }
    public function tasks(string $upid): TaskApi
    {
        return new TaskApi($this->client, $this->node, $upid);
    }
}
