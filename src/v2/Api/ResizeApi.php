<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use GridCP\Proxmox\Api\Parameters\ResizeParameters;
use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\Qemu\ResizeResult;
use GridCP\Proxmox\Result\ResultConverter;
use GridCP\Proxmox\Result\ResultConverterInterface;
use GridCP\Proxmox\Result\ResultInterface;

/**
 * @see https://pve.proxmox.com/pve-docs/api-viewer/#/nodes/{node}/qemu/{vmid}/resize
 */
class ResizeApi
{
    public function __construct(
        private readonly ProxmoxApiClient $client,
        private readonly string $node,
        private readonly int $vmId,
        private readonly ResultConverterInterface $resultConverter = new ResultConverter(),
    ) {
    }

    public function resize(ResizeParameters $parameters): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/resize', $this->node, $this->vmId);

        if (null !== $parameters) {
            $query = $parameters->toArray();

            if (false === empty($query)) {
                $url .= '?' . http_build_query($query);
            }
        }

        $response = $this->client->put($url);

        return $this->resultConverter->convert($response, ResizeResult::class);
    }
}
