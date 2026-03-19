<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result\Storage;

use GridCP\Proxmox\Result\ResultInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;

final readonly class NodeStorageResult implements ResultInterface
{
    public function __construct(
        public string $content,
        public string $storage,
        public string $type,
        public ?int $active = null,
        public ?int $avail = null,
        public ?int $enabled = null,
        public ?string $formats = null,
        #[SerializedName('select_existing')]
        public ?string $selectExisting = null,
        public ?int $shared = null,
        public ?int $total = null,
        public ?int $used = null,
        #[SerializedName('used_fraction')]
        public ?float $usedFraction = null,
    ) {
    }
}
