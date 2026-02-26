<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use GridCP\Proxmox\Api\Result\RebootResult;
use GridCP\Proxmox\Api\Result\ResetResult;
use GridCP\Proxmox\Api\Result\ResultConverter;
use GridCP\Proxmox\Api\Result\ShoutdownResult;
use GridCP\Proxmox\Api\Result\SuspendResult;

class StatusApi implements StatusApiInterface
{
    public function __construct(
        private ProxmoxApiClient $client,
        private string $node,
        private string $vmid,
    ) {
    }

    public function shoutdown(): ShoutdownResult
    {
        $response = $this->client->request('POST', '/api2/json/nodes/{node}/qemu/{vmid}/status/shutdown', [
            'vars' => [
                'node' => $this->node,
                'vmid' => $this->vmid,
            ],
        ]);

        $converter = new ResultConverter();

        return $converter->convert($response, ShoutdownResult::class);
    }

    public function suspend(): SuspendResult
    {
        $response = $this->client->request('POST', '/api2/json/nodes/{node}/qemu/{vmid}/status/suspend', [
            'vars' => [
                'node' => $this->node,
                'vmid' => $this->vmid,
            ],
        ]);

        $converter = new ResultConverter();

        return $converter->convert($response, SuspendResult::class);
    }

    public function reboot(): RebootResult
    {
        $response = $this->client->request('POST', '/api2/json/nodes/{node}/qemu/{vmid}/status/reboot', [
            'vars' => [
                'node' => $this->node,
                'vmid' => $this->vmid,
            ],
        ]);

        $converter = new ResultConverter();

        return $converter->convert($response, RebootResult::class);
    }

    public function reset(): ResetResult
    {
        $response = $this->client->request('POST', '/api2/json/nodes/{node}/qemu/{vmid}/status/reset', [
            'vars' => [
                'node' => $this->node,
                'vmid' => $this->vmid,
            ],
        ]);

        $converter = new ResultConverter();

        return $converter->convert($response, ResetResult::class);
    }
}
