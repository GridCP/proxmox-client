<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\ResultConverter;
use GridCP\Proxmox\Result\TaskResult;

class TaskApi
{
    public function __construct(
        private ProxmoxApiClient $client,
        private string $node,
        private string $upid,
    ) {
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function status(): TaskResult
    {
        $response = $this->client->request('GET', '/api2/json/nodes/{node}/tasks/{upid}/status', [
            'vars' => [
                'node' => $this->node,
                'upid' => $this->upid,
            ],
        ]);

        $converter = new ResultConverter();

        return $converter->convert($response, TaskResult::class);
    }
}
