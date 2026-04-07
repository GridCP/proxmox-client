<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\Capabilities\CpuResult;
use GridCP\Proxmox\Result\ResultConverter;
use GridCP\Proxmox\Result\ResultConverterInterface;

class CapabilitiesApi
{
    public function __construct(
        private readonly ProxmoxApiClient $client,
        private readonly string $node,
        private readonly ResultConverterInterface $resultConverter = new ResultConverter(),
    ) {
    }

    /**
     * @see https://pve.proxmox.com/pve-docs/api-viewer/#/nodes/{node}/capabilities/qemu/cpu
     *
     * @return CpuResult[]
     */
    public function cpu(): array
    {
        $url = sprintf('/api2/json/nodes/%s/capabilities/qemu/cpu', $this->node);
        $response = $this->client->get($url);

        return $this->resultConverter->convert($response, CpuResult::class . '[]');
    }

    /**
     * @see https://pve.proxmox.com/pve-docs/api-viewer/#/nodes/{node}/capabilities/qemu/cpu-flags
     *
     * @return array<int, array<string, mixed>>
     */
    public function cpuFlags(): array
    {
        $url = sprintf('/api2/json/nodes/%s/capabilities/qemu/cpu-flags', $this->node);
        $response = $this->client->get($url);

        /** @var array<int, array<string, mixed>> $result */
        $result = $this->resultConverter->convert($response, 'array');

        return $result;
    }

    /**
     * @see https://pve.proxmox.com/pve-docs/api-viewer/#/nodes/{node}/capabilities/qemu/machines
     *
     * @return array<int, array<string, mixed>>
     */
    public function machines(): array
    {
        $url = sprintf('/api2/json/nodes/%s/capabilities/qemu/machines', $this->node);
        $response = $this->client->get($url);

        /** @var array<int, array<string, mixed>> $result */
        $result = $this->resultConverter->convert($response, 'array');

        return $result;
    }

    /**
     * @see https://pve.proxmox.com/pve-docs/api-viewer/#/nodes/{node}/capabilities/qemu/migration
     *
     * @return array<string, mixed>
     */
    public function migration(): array
    {
        $url = sprintf('/api2/json/nodes/%s/capabilities/qemu/migration', $this->node);
        $response = $this->client->get($url);

        /** @var array<string, mixed> $result */
        $result = $this->resultConverter->convert($response, 'array');

        return $result;
    }
}
