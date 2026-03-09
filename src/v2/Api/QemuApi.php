<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use GridCP\Proxmox\Api\Parameters\DestroyParameters;
use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\DestroyResult;
use GridCP\Proxmox\Result\ResultConverter;
use GridCP\Proxmox\Result\ResultConverterInterface;
use GridCP\Proxmox\Result\ResultInterface;
use Psr\Http\Message\ResponseInterface;

class QemuApi
{
    public function __construct(
        private readonly ProxmoxApiClient $client,
        private readonly string $node,
        private readonly int $vmId,
        private readonly ResultConverterInterface $resultConverter = new ResultConverter(),
    ) {
    }

    /**
     * Directory index.
     *
     * @see https://pve.proxmox.com/pve-docs/api-viewer/index.html#/nodes/{node}/qemu/{vmid}
     */
    public function index(): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s', $this->node, $this->vmId);

        $response = $this->client->get($url);

        return $this->resultConverter->convert($response);
    }

    /**
     * Destroy the VM and all used/owned volumes. Removes any VM specific permissions and firewall rules.
     *
     * @see https://pve.proxmox.com/pve-docs/api-viewer/index.html#/nodes/{node}/qemu/{vmid}
     */
    public function destroy(?DestroyParameters $parameters = null): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s', $this->node, $this->vmId);

        if (null !== $parameters) {
            $query = $parameters->toArray();

            if (false === empty($query)) {
                $url .= '?' . http_build_query($query);
            }
        }

        $response = $this->delete($url);

        return $this->resultConverter->convert($response, DestroyResult::class);
    }

    public function status(): StatusApi
    {
        return new StatusApi($this->client, $this->node, $this->vmId);
    }

    public function delete(string $url): ResponseInterface
    {
        return $this->client->delete($url);
    }
}
