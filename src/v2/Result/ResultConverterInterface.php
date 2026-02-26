<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Result;

interface ResultConverterInterface
{
    public function convert(RawResultInterface $result, string $resultType, array $options = []): ResultInterface;
}
