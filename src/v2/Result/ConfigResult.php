<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result;

class ConfigResult implements ResultInterface
{
    public function __construct(
        public readonly string $digest,
        public readonly ?string $boot = null,
        public readonly ?string $memory = null,
        public readonly ?string $parent = null,
        public readonly ?string $net0 = null,
        public readonly ?int $cores = null,
        public readonly ?string $meta = null,
        public readonly ?int $onboot = null,
        public readonly ?string $name = null,
        public readonly ?string $ostype = null,
        public readonly ?int $balloon = null,
        public readonly ?string $ide2 = null,
        public readonly ?string $ide0 = null,
        public readonly ?string $smbios1 = null,
        public readonly ?string $tags = null,
        public readonly ?string $cipassword = null,
        public readonly ?string $scsi0 = null,
        public readonly ?string $ciuser = null,
        public readonly ?string $keyboard = null,
        public readonly ?string $cpu = null,
        public readonly ?string $ipconfig0 = null,
        public readonly ?string $agent = null,
        public readonly ?string $vmgenid = null,
        public readonly ?string $scsihw = null,
    ) {
    }
}
