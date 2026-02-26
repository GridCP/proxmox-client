<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Result;

final readonly class TaskResult implements ResultInterface
{
    public const STATUS_RUNNING = 'running';
    public const STATUS_STOPPED = 'stopped';

    public function __construct(
        public ?string $id,
        public ?string $user,
        public ?string $exitstatus,
        public ?string $status,
        public ?int $pstart,
        public ?int $starttime,
        public ?string $type,
        public ?string $upid,
        public ?int $pid,
        public ?string $node,
    ) {
    }

    /**
     * @param array{
     *   data: array{
     *     id: string,
     *     user: string,
     *     exitstatus: string,
     *     status: 'running'|'stopped',
     *     pid: int,
     *     node: string,
     *     upid: string,
     *     type: string,
     *     starttime: int,
     *     pstart: int,
     *   }
     * } $result
     */
    public static function fromArray(array $result): self
    {
        $data = $result['data'] ?? [];

        return new self(
            $data['id'] ?? null,
            $data['user'] ?? null,
            $data['exitstatus'] ?? null,
            $data['status'] ?? null,
            $data['pstart'] ?? null,
            $data['starttime'] ?? null,
            $data['type'] ?? null,
            $data['upid'] ?? null,
            $data['pid'] ?? null,
            $data['node'] ?? null,
        );
    }
}
