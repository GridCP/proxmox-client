<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use GridCP\Proxmox\Api\Parameters\Qemu\WriteFileParameters;
use GridCP\Proxmox\ProxmoxApiClient;
use GridCP\Proxmox\Result\Qemu\FileWriteResult;
use GridCP\Proxmox\Result\ResultConverter;
use GridCP\Proxmox\Result\ResultConverterInterface;
use GridCP\Proxmox\Result\ResultInterface;

class AgentApi
{
    public function __construct(
        private readonly ProxmoxApiClient $client,
        private readonly string $node,
        private readonly int $vmId,
        private readonly ResultConverterInterface $resultConverter = new ResultConverter(),
    ) {
    }

    public function writeFile(WriteFileParameters $parameters): ResultInterface
    {
        $url = sprintf('/api2/json/nodes/%s/qemu/%s/agent/file-write', $this->node, $this->vmId);

        $body = '';
        $parameters = $parameters->toArray();
        if (false === empty($parameters)) {
            $body = http_build_query($parameters, '', '&', \PHP_QUERY_RFC3986);
        }

        $response = $this->client->post($url, [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ], $body);

        return $this->resultConverter->convert($response, FileWriteResult::class);
    }
}
