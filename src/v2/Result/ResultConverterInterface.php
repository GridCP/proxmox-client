<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Result;

use Psr\Http\Message\ResponseInterface;

interface ResultConverterInterface
{
    public function convert(ResponseInterface $response, string $resultType, array $options = []): ResultInterface;
}
