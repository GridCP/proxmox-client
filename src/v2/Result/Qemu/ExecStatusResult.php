<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result\Qemu;

use GridCP\Proxmox\Result\ResultInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;

final class ExecStatusResult implements ResultInterface
{
    public function __construct(
        public readonly bool $exited,
        #[SerializedName('err-data')]
        public readonly ?string $errData = null,
        #[SerializedName('err-truncated')]
        public readonly ?bool $errTruncated = null,
        public readonly ?int $exitcode = null,
        #[SerializedName('out-data')]
        public readonly ?string $outData = null,
        #[SerializedName('out-truncated')]
        public readonly ?bool $outTruncated = null,
        public readonly ?int $signal = null,
    ) {
    }

    public function isSuccess(): bool
    {
        return false === $this->exited;
    }

    public function isError(): bool
    {
        return true === $this->exited;
    }
}
