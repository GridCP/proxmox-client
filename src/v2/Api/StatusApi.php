<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use GridCP\Proxmox\Api\Parameters\RebootParameters;
use GridCP\Proxmox\Api\Parameters\ResetParameters;
use GridCP\Proxmox\Api\Parameters\ResumeParameters;
use GridCP\Proxmox\Api\Parameters\ShutdownParameters;
use GridCP\Proxmox\Api\Parameters\StartParameters;
use GridCP\Proxmox\Api\Parameters\StopParameters;
use GridCP\Proxmox\Api\Parameters\SuspendParameters;
use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\CurrentResult;
use GridCP\Proxmox\Result\RebootResult;
use GridCP\Proxmox\Result\ResetResult;
use GridCP\Proxmox\Result\ResultConverter;
use GridCP\Proxmox\Result\ResultConverterInterface;
use GridCP\Proxmox\Result\ResultInterface;
use GridCP\Proxmox\Result\ResumeResult;
use GridCP\Proxmox\Result\ShutdownResult;
use GridCP\Proxmox\Result\StartResult;
use GridCP\Proxmox\Result\StatusResult;
use GridCP\Proxmox\Result\StopResult;
use GridCP\Proxmox\Result\SuspendResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class StatusApi implements StatusApiInterface
{
    public function __construct(
        private readonly ProxmoxApiClient $client,
        private readonly string $node,
        private readonly int $vmId,
        private readonly ResultConverterInterface $resultConverter = new ResultConverter(),
    ) {
    }

    /** @see https://pve.proxmox.com/pve-docs/api-viewer/#/nodes/{node}/qemu/{vmid}/status */
    public function status(): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status', $this->node, $this->vmId);
        $response = $this->get($url);

        return $this->resultConverter->convert($response, StatusResult::class);
    }

    /** @see https://pve.proxmox.com/pve-docs/api-viewer/index.html#/nodes/{node}/qemu/{vmid}/status/current */
    public function current(): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/current', $this->node, $this->vmId);
        $response = $this->get($url);

        return $this->resultConverter->convert($response, CurrentResult::class);
    }

    /** @see https://pve.proxmox.com/pve-docs/api-viewer/index.html#/nodes/{node}/qemu/{vmid}/status/reboot */
    public function reboot(?RebootParameters $parameters = null): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/reboot', $this->node, $this->vmId);

        if (null !== $parameters) {
            $query = $parameters->toArray();

            if (false === empty($query)) {
                $url .= '?' . http_build_query($query);
            }
        }

        $response = $this->post($url);

        return $this->resultConverter->convert($response, RebootResult::class);
    }

    /** @see https://pve.proxmox.com/pve-docs/api-viewer/index.html#/nodes/{node}/qemu/{vmid}/status/reset */
    public function reset(?ResetParameters $parameters = null): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/reset', $this->node, $this->vmId);

        if (null !== $parameters) {
            $query = $parameters->toArray();

            if (false === empty($query)) {
                $url .= '?' . http_build_query($query);
            }
        }

        $response = $this->client->post($url);

        return $this->resultConverter->convert($response, ResetResult::class);
    }

    /** @see https://pve.proxmox.com/pve-docs/api-viewer/index.html#/nodes/{node}/qemu/{vmid}/status/resume */
    public function resume(?ResumeParameters $parameters = null): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/resume', $this->node, $this->vmId);

        if (null !== $parameters) {
            $query = $parameters->toArray();

            if (false === empty($query)) {
                $url .= '?' . http_build_query($query);
            }
        }

        $response = $this->post($url);

        return $this->resultConverter->convert($response, ResumeResult::class);
    }

    /** @see https://pve.proxmox.com/pve-docs/api-viewer/index.html#/nodes/{node}/qemu/{vmid}/status/shutdown */
    public function shutdown(?ShutdownParameters $parameters = null): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/shutdown', $this->node, $this->vmId);

        if (null !== $parameters) {
            $query = $parameters->toArray();

            if (false === empty($query)) {
                $url .= '?' . http_build_query($query);
            }
        }

        $response = $this->post($url);

        return $this->resultConverter->convert($response, ShutdownResult::class);
    }

    /** @see https://pve.proxmox.com/pve-docs/api-viewer/index.html#/nodes/{node}/qemu/{vmid}/status/start */
    public function start(?StartParameters $parameters = null): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/start', $this->node, $this->vmId);

        if (null !== $parameters) {
            $query = $parameters->toArray();

            if (false === empty($query)) {
                $url .= '?' . http_build_query($query);
            }
        }

        $response = $this->post($url);

        return $this->resultConverter->convert($response, StartResult::class);
    }

    /** @see https://pve.proxmox.com/pve-docs/api-viewer/index.html#/nodes/{node}/qemu/{vmid}/status/stop */
    public function stop(?StopParameters $parameters = null): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/stop', $this->node, $this->vmId);

        if (null !== $parameters) {
            $query = $parameters->toArray();

            if (false === empty($query)) {
                $url .= '?' . http_build_query($query);
            }
        }
        $response = $this->post($url);

        return $this->resultConverter->convert($response, StopResult::class);
    }

    /** @see https://pve.proxmox.com/pve-docs/api-viewer/index.html#/nodes/{node}/qemu/{vmid}/status/suspend */
    public function suspend(?SuspendParameters $parameters = null): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/suspend', $this->node, $this->vmId);

        if (null !== $parameters) {
            $query = $parameters->toArray();

            if (false === empty($query)) {
                $url .= '?' . http_build_query($query);
            }
        }

        $response = $this->post($url);

        return $this->resultConverter->convert($response, SuspendResult::class);
    }

    protected function get(string $url, array $headers = []): ResponseInterface
    {
        return $this->client->get($url, $headers);
    }

    private function post(
        string $url,
        array $headers = [],
        string|StreamInterface|null $body = null,
    ): ResponseInterface {
        return $this->client->post($url, $headers, $body);
    }
}
