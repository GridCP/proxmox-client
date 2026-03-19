<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\NodeResult;
use GridCP\Proxmox\Result\ResultConverter;
use GridCP\Proxmox\Result\ResultConverterInterface;
use Psr\Http\Message\ResponseInterface;

class NodeApi
{
    public function __construct(
        private readonly ProxmoxApiClient $client,
        private readonly ?string $node = null,
        private readonly ResultConverterInterface $resultConverter = new ResultConverter(),
    ) {
    }

    /**
     * @return NodeResult[]
     */
    public function nodes(): array
    {
        $url = sprintf('/api2/json/nodes');

        $response = $this->get($url);

        return $this->resultConverter->convert($response, NodeResult::class . '[]');
    }

    public function qemu(int $vmid): QemuApi
    {
        return new QemuApi($this->client, $this->requireNode(), $vmid);
    }

    public function storage(?string $storage = null): StorageApi
    {
        return new StorageApi($this->client, $this->requireNode(), $storage);
    }

    public function tasks(string $upid): TaskApi
    {
        return new TaskApi($this->client, $this->requireNode(), $upid);
    }

    protected function get(string $url, array $headers = []): ResponseInterface
    {
        return $this->client->get($url, $headers);
    }

    private function requireNode(): string
    {
        if (null === $this->node || '' === $this->node) {
            throw new \LogicException('Node name is required for node-scoped endpoints');
        }

        return $this->node;
    }
}
