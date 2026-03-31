<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use GridCP\Proxmox\Api\Parameters\ConfigureParameters;
use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\Result\ConfigResult;
use GridCP\Proxmox\Result\Result\Qemu\VirtualMachineConfigAsync;
use GridCP\Proxmox\Result\Result\ResultConverter;
use GridCP\Proxmox\Result\Result\ResultConverterInterface;
use GridCP\Proxmox\Result\Result\ResultInterface;

/**
 * @see https://pve.proxmox.com/pve-docs/api-viewer/#/nodes/{node}/qemu/{vmid}/config
 */
class ConfigApi
{
    public function __construct(
        private readonly ProxmoxApiClient $client,
        private readonly string $node,
        private readonly int $vmId,
        private readonly ResultConverterInterface $resultConverter = new ResultConverter(),
    ) {
    }

    public function get(): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/config', $this->node, $this->vmId);

        $response = $this->client->get($url);

        return $this->resultConverter->convert($response, ConfigResult::class);
    }

    public function set(?ConfigureParameters $parameters = null): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/config', $this->node, $this->vmId);

        if (null !== $parameters) {
            $query = $parameters->toArray();

            if (false === empty($query)) {
                $url .= '?' . http_build_query($query);
            }
        }

        $response = $this->client->post($url);

        return $this->resultConverter->convert($response, VirtualMachineConfigAsync::class);
    }
}
