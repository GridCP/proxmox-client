<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Result\Normalizer;

use GridCP\Proxmox\Api\Result\CurrentResult;
use GridCP\Proxmox\Api\Result\DestroyResult;
use GridCP\Proxmox\Api\Result\RebootResult;
use GridCP\Proxmox\Api\Result\ResetResult;
use GridCP\Proxmox\Api\Result\ResumeResult;
use GridCP\Proxmox\Api\Result\ShoutdownResult;
use GridCP\Proxmox\Api\Result\StartResult;
use GridCP\Proxmox\Api\Result\StatusResult;
use GridCP\Proxmox\Api\Result\StopResult;
use GridCP\Proxmox\Api\Result\SuspendResult;
use GridCP\Proxmox\Api\Result\TaskResult;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class ApiResultDenormalizer implements DenormalizerInterface
{
    public const array SUPPORTED_TYPES = [
        DestroyResult::class => true,
        StatusResult::class => true,
        CurrentResult::class => true,
        RebootResult::class => true,
        ResetResult::class => true,
        ResumeResult::class => true,
        ShoutdownResult::class => true,
        StartResult::class => true,
        StopResult::class => true,
        TaskResult::class => true,
        SuspendResult::class => true,
    ];

    private readonly ObjectNormalizer $objectNormalizer;

    public function __construct(?ObjectNormalizer $objectNormalizer = null)
    {
        $this->objectNormalizer = $objectNormalizer ?? new ObjectNormalizer();
    }

    public function getSupportedTypes(?string $format): array
    {
        return self::SUPPORTED_TYPES;
    }

    public function supportsDenormalization(
        mixed $data,
        string $type,
        ?string $format = null,
        array $context = [],
    ): bool {
        return isset(self::SUPPORTED_TYPES[$type]);
    }

    public function denormalize(
        mixed $data,
        string $type,
        ?string $format = null,
        array $context = [],
    ): mixed {
        if (false === is_array($data)) {
            throw new \InvalidArgumentException('Expected decoded JSON response as array.');
        }

        $normalizedData = $this->normalizeDataForType($type, $data);

        return $this->objectNormalizer->denormalize($normalizedData, $type, $format, $context);
    }

    private function normalizeDataForType(string $type, array $data): array
    {
        return match ($type) {
            TaskResult::class => $this->normalizeTaskData($data),
            DestroyResult::class,
            StatusResult::class, CurrentResult::class => [
                'data' => is_array($data['data'] ?? null) ? $data['data'] : [],
            ],
            ShoutdownResult::class,
            SuspendResult::class,
            RebootResult::class,
            ResetResult::class,
            ResumeResult::class,
            StopResult::class,
            StartResult::class => [
                'upid' => isset($data['data']) ? (string) $data['data'] : null,
            ],
            default => throw new \RuntimeException('Unsupported result type.'),
        };
    }

    private function normalizeTaskData(array $data): array
    {
        $taskData = is_array($data['data'] ?? null) ? $data['data'] : [];

        return [
            'id' => isset($taskData['id']) ? (string) $taskData['id'] : null,
            'user' => isset($taskData['user']) ? (string) $taskData['user'] : null,
            'exitstatus' => isset($taskData['exitstatus']) ? (string) $taskData['exitstatus'] : null,
            'status' => isset($taskData['status']) ? (string) $taskData['status'] : null,
            'pstart' => isset($taskData['pstart']) ? (int) $taskData['pstart'] : null,
            'starttime' => isset($taskData['starttime']) ? (int) $taskData['starttime'] : null,
            'type' => isset($taskData['type']) ? (string) $taskData['type'] : null,
            'upid' => isset($taskData['upid']) ? (string) $taskData['upid'] : null,
            'pid' => isset($taskData['pid']) ? (int) $taskData['pid'] : null,
            'node' => isset($taskData['node']) ? (string) $taskData['node'] : null,
        ];
    }
}
