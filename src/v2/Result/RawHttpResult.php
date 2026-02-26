<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

readonly class RawHttpResult implements RawResultInterface
{
    public function __construct(
        private ResponseInterface $response,
    ) {
    }

    public function getData(): array
    {
        return $this->response->toArray(false);
    }

    public function getObject(): ResponseInterface
    {
        return $this->response;
    }
}
