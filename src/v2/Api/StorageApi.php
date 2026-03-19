<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use GridCP\Proxmox\Api\Parameters\Storage\ListStorageParameters;
use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\ResultConverter;
use GridCP\Proxmox\Result\ResultConverterInterface;
use GridCP\Proxmox\Result\ResultInterface;
use GridCP\Proxmox\Result\Storage\NodeStorageResult;
use GridCP\Proxmox\Result\Storage\StatusResult;

class StorageApi
{
    public function __construct(
        private readonly ProxmoxApiClient $client,
        private readonly string $node,
        private readonly ?string $storage = null,
        private readonly ResultConverterInterface $resultConverter = new ResultConverter(),
    ) {
    }

    /**
     * @return NodeStorageResult[]
     */
    public function list(?ListStorageParameters $parameters = null): array
    {
        $url = sprintf('/api2/json/nodes/%s/storage', $this->node);

        if (null !== $parameters) {
            $query = $parameters->toArray();

            if (false === empty($query)) {
                $url .= '?' . http_build_query($query);
            }
        }

        $response = $this->client->get($url);

        return $this->resultConverter->convert($response, NodeStorageResult::class . '[]');
    }

    /**
     * @see https://pve.proxmox.com/pve-docs/api-viewer/#/nodes/{node}/storage/{storage}/status
     */
    public function status(): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/storage/%s/status', $this->node, $this->storage);

        $response = $this->client->get($url);

        /** @var NodeStorageResult $result */
        $result = $this->resultConverter->convert($response, StatusResult::class);

        return $result;
    }
}
