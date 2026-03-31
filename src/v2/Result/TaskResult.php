<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result;

final readonly class TaskResult implements ResultInterface
{
    public const STATUS_RUNNING = 'running';
    public const STATUS_STOPPED = 'stopped';

    public function __construct(
        public ?string $id,
        public ?string $user,
        public ?string $exitstatus,
        /** @var 'running'|'stopped' */
        public ?string $status,
        public ?int $pstart,
        public ?int $starttime,
        /** @var 'qmdestroy' */
        public ?string $type,
        public ?string $upid,
        public ?int $pid,
        public ?string $node,
    ) {
    }

    public function isStopped(): bool
    {
        return self::STATUS_STOPPED === $this->status;
    }
}
