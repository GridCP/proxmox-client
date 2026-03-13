<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result;

use Symfony\Component\Serializer\Attribute\SerializedName;

final readonly class NodeResult implements ResultInterface
{
    public function __construct(
        public ?float $cpu = null,
        public ?int $mem = null,
        public ?string $node = null,
        #[SerializedName('maxdisk')]
        public ?int $maxDisk = null,
        #[SerializedName('maxmem')]
        public ?int $maxMem = null,
        public ?string $level = null,
        #[SerializedName('maxcpu')]
        public ?int $maxCpu = null,
        public ?string $id = null,
        public ?string $type = null,
        public ?int $uptime = null,
        public ?string $status = null,
        public ?int $disk = null,
        #[SerializedName('ssl_fingerprint')]
        public ?string $sslFingerprint = null,
    ) {
    }
}
