<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\ResultConverter;
use GridCP\Proxmox\Result\ResultConverterInterface;
use GridCP\Proxmox\Result\ResultInterface;
use GridCP\Proxmox\Result\TaskResult;

class TaskApi
{
    public function __construct(
        private ProxmoxApiClient $client,
        private string $node,
        private string $upid,
        private readonly ResultConverterInterface $resultConverter = new ResultConverter(),
    ) {
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function status(): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/tasks/%s/status', $this->node, $this->upid);

        $response = $this->client->get($url);

        return $this->resultConverter->convert($response, TaskResult::class);
    }
}
