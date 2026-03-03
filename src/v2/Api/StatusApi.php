<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Api;

use GridCP\Proxmox\Api\ProxmoxApiClient;
use GridCP\Proxmox\Api\Result\CurrentResult;
use GridCP\Proxmox\Api\Result\RebootResult;
use GridCP\Proxmox\Api\Result\ResetResult;
use GridCP\Proxmox\Api\Result\ResultConverter;
use GridCP\Proxmox\Api\Result\ResultInterface;
use GridCP\Proxmox\Api\Result\ShoutdownResult;
use GridCP\Proxmox\Api\Result\StartResult;
use GridCP\Proxmox\Api\Result\StatusResult;
use GridCP\Proxmox\Api\Result\SuspendResult;
use Psr\Http\Message\ResponseInterface;

class StatusApi implements StatusApiInterface
{
    public function __construct(
        private ProxmoxApiClient $client,
        private string $node,
        private string $vmid,
    ) {
    }

    /**
     * @see https://pve.proxmox.com/pve-docs/api-viewer/#/nodes/{node}/qemu/{vmid}/status
     */
    public function status(): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status', $this->node, $this->vmid);
        $response = $this->get($url);

        $converter = new ResultConverter();

        return $converter->convert($response, StatusResult::class);
    }

    public function current(): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/current', $this->node, $this->vmid);
        $response = $this->get($url);

        $converter = new ResultConverter();

        return $converter->convert($response, CurrentResult::class);
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

    public function start(): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/start', $this->node, $this->vmid);
        $response = $this->post($url);

        $converter = new ResultConverter();

        return $converter->convert($response, StartResult::class);
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

    public function resume()
    {
        // TODO: Implement resume() method.
    }

    public function stop()
    {
        // TODO: Implement stop() method.
    }

    protected function get(string $url, array $headers = []): ResponseInterface
    {
        return $this->client->get($url, $headers);
    }

    private function post(string $url, array $headers = [], array $body = []): ResponseInterface
    {
        return $this->client->post($url, $headers, $body);
    }
}
