<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Api;

use GridCP\Proxmox\Api\Api\Parameters\StartParameters;
use GridCP\Proxmox\Api\Api\Parameters\StopParameters;
use GridCP\Proxmox\Api\Api\Parameters\SuspendParameters;
use GridCP\Proxmox\Api\ProxmoxApiClient;
use GridCP\Proxmox\Api\Result\CurrentResult;
use GridCP\Proxmox\Api\Result\RebootResult;
use GridCP\Proxmox\Api\Result\ResetResult;
use GridCP\Proxmox\Api\Result\ResultConverter;
use GridCP\Proxmox\Api\Result\ResultConverterInterface;
use GridCP\Proxmox\Api\Result\ResultInterface;
use GridCP\Proxmox\Api\Result\ResumeResult;
use GridCP\Proxmox\Api\Result\ShoutdownResult;
use GridCP\Proxmox\Api\Result\StartResult;
use GridCP\Proxmox\Api\Result\StatusResult;
use GridCP\Proxmox\Api\Result\StopResult;
use GridCP\Proxmox\Api\Result\SuspendResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class StatusApi implements StatusApiInterface
{
    public function __construct(
        private readonly ProxmoxApiClient $client,
        private readonly string $node,
        private readonly string $vmid,
        private readonly ResultConverterInterface $resultConverter = new ResultConverter(),
    ) {
    }

    /** @see https://pve.proxmox.com/pve-docs/api-viewer/#/nodes/{node}/qemu/{vmid}/status */
    public function status(): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status', $this->node, $this->vmid);
        $response = $this->get($url);

        $converter = new ResultConverter();

        return $converter->convert($response, StatusResult::class);
    }

    /** @see https://pve.proxmox.com/pve-docs/api-viewer/index.html#/nodes/{node}/qemu/{vmid}/status/current */
    public function current(): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/current', $this->node, $this->vmid);
        $response = $this->get($url);

        $converter = new ResultConverter();

        return $converter->convert($response, CurrentResult::class);
    }

    /** @see https://pve.proxmox.com/pve-docs/api-viewer/index.html#/nodes/{node}/qemu/{vmid}/status/reboot */
    public function reboot(?int $timeout = null): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/reboot', $this->node, $this->vmid);
        if (null !== $timeout) {
            $url .= '?' . http_build_query(['timeout' => $timeout]);
        }

        $response = $this->post($url);

        $converter = new ResultConverter();

        return $converter->convert($response, RebootResult::class);
    }

    /** @see https://pve.proxmox.com/pve-docs/api-viewer/index.html#/nodes/{node}/qemu/{vmid}/status/reset */
    public function reset(bool $skiplock = false): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/reset', $this->node, $this->vmid);
        if (true === $skiplock) {
            $url .= '?' . http_build_query(['skiplock' => $skiplock]);
        }

        $response = $this->client->post($url);

        $converter = new ResultConverter();

        return $converter->convert($response, ResetResult::class);
    }

    /** @see https://pve.proxmox.com/pve-docs/api-viewer/index.html#/nodes/{node}/qemu/{vmid}/status/resume */
    public function resume(bool $nocheck = false, bool $skiplock = false): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/resume', $this->node, $this->vmid);
        $params = [];
        if (true === $nocheck) {
            $params['nocheck'] = $nocheck;
        }
        if (true === $skiplock) {
            $params['skiplock'] = $skiplock;
        }
        if (false === empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $response = $this->post($url);

        $converter = new ResultConverter();

        return $converter->convert($response, ResumeResult::class);
    }

    /** @see https://pve.proxmox.com/pve-docs/api-viewer/index.html#/nodes/{node}/qemu/{vmid}/status/shutdown */
    public function shoutdown(
        bool $forceStop = false,
        bool $keepActive = false,
        bool $skiplock = false,
        ?int $timeout = null,
    ): ResultInterface {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/shutdown', $this->node, $this->vmid);
        $params = [];
        if (true === $forceStop) {
            $params['forceStop'] = $forceStop;
        }
        if (true === $keepActive) {
            $params['keepActive'] = $keepActive;
        }
        if (true === $skiplock) {
            $params['skiplock'] = $skiplock;
        }
        if (null !== $timeout) {
            $params['timeout'] = $timeout;
        }
        if (false === empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $response = $this->post($url);

        return $this->resultConverter->convert($response, ShoutdownResult::class);
    }

    /** @see https://pve.proxmox.com/pve-docs/api-viewer/index.html#/nodes/{node}/qemu/{vmid}/status/start */
    public function start(?StartParameters $parameters = null): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/start', $this->node, $this->vmid);

        if (null !== $parameters) {
            $query = $parameters->toArray();

            if (false === empty($query)) {
                $url .= '?' . http_build_query($query);
            }
        }

        $response = $this->post($url);

        return $this->resultConverter->convert($response, StartResult::class);
    }

    public function stop(?StopParameters $parameters = null): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/stop', $this->node, $this->vmid);

        if (null !== $parameters) {
            $query = $parameters->toArray();

            if (false === empty($query)) {
                $url .= '?' . http_build_query($query);
            }
        }
        $response = $this->post($url);

        return $this->resultConverter->convert($response, StopResult::class);
    }

    public function suspend(?SuspendParameters $parameters = null): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/status/suspend', $this->node, $this->vmid);

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
