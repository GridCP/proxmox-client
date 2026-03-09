<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result;

use Symfony\Component\Serializer\Attribute\SerializedName;

class CurrentResult implements ResultInterface
{
    public function __construct(
        public readonly ?array $ha = null,
        public readonly ?string $status = null,
        #[SerializedName('vmid')]
        public readonly ?int $vmId = null,
        public readonly ?bool $agent = null,
        public readonly ?string $clipboard = null,
        public readonly ?float $cpu = null,
        public readonly ?float $cpus = null,
        #[SerializedName('diskread')]
        public readonly ?int $diskRead = null,
        #[SerializedName('diskwrite')]
        public readonly ?int $diskWrite = null,
        public readonly ?string $lock = null,
        #[SerializedName('maxdisk')]
        public readonly ?int $maxDisk = null,
        #[SerializedName('maxmem')]
        public readonly ?int $maxMem = null,
        public readonly ?int $mem = null,
        #[SerializedName('memhost')]
        public readonly ?int $memHost = null,
        public readonly ?string $name = null,
        #[SerializedName('netin')]
        public readonly ?int $netIn = null,
        #[SerializedName('netout')]
        public readonly ?int $netOut = null,
        public readonly ?int $pid = null,
        #[SerializedName('pressurecpufull')]
        public readonly ?float $pressureCpuFull = null,
        #[SerializedName('pressurecpusome')]
        public readonly ?float $pressureCpuSome = null,
        #[SerializedName('pressureiofull')]
        public readonly ?float $pressureIoFull = null,
        #[SerializedName('pressureiosome')]
        public readonly ?float $pressureIoSome = null,
        #[SerializedName('pressurememoryfull')]
        public readonly ?float $pressureMemoryFull = null,
        #[SerializedName('pressurememorysome')]
        public readonly ?float $pressureMemorySome = null,
        #[SerializedName('qmpstatus')]
        public readonly ?string $qmpStatus = null,
        #[SerializedName('running-machine')]
        public readonly ?string $runningMachine = null,
        #[SerializedName('running-qemu')]
        public readonly ?string $runningQemu = null,
        #[SerializedName('serial')]
        public readonly ?bool $serial = null,
        public readonly ?bool $spice = null,
        public readonly ?string $tags = null,
        public readonly ?bool $template = null,
        public readonly ?int $uptime = null,
    ) {
    }
}
