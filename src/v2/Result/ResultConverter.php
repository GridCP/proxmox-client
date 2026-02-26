<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Result;

use GridCP\Proxmox\Api\Exception\AuthenticationException;

class ResultConverter implements ResultConverterInterface
{
    public function convert(RawResultInterface $result, string $resultType, array $options = []): ResultInterface
    {
        $response = $result->getObject();

        if (501 === $response->getStatusCode()) {
            /**
             * @var array{
             *     data: null,
             *     message: string,
             * } $data
             */
            $data = json_decode($response->getContent(), true);
            throw new \RuntimeException($data['message']);
        }

        if (500 === $response->getStatusCode()) {
            /**
             * @var array{
             *     data: null,
             *     message: string,
             * } $data
             */
            $data = json_decode($response->getContent(), true);
            throw new \RuntimeException($data['message']);
        }

        if (400 === $response->getStatusCode()) {
            /**
             * @var array{
             *     data: null,
             *     message: string,
             *     errors: array<string, string>
             * } $data
             */
            $data = json_decode($response->getContent(), true);
            throw new \RuntimeException($data['message']);
        }

        if (401 == $response->getStatusCode()) {
            /**
             * @var array{
             *     data: null,
             *     message: string,
             * } $data
             */
            $data = json_decode($response->getContent(), true);
            throw new AuthenticationException($data['message']);
        }

        $data = $result->getData();

        return $this->normalize($resultType, $data);
    }

    private function normalize(string $resultType, array $data): ResultInterface
    {
        return match ($resultType) {
            TaskResult::class => TaskResult::fromArray($data),
            ShoutdownResult::class => ShoutdownResult::fromArray($data),
            SuspendResult::class => SuspendResult::fromArray($data),
            RebootResult::class => RebootResult::fromArray($data),
            ResetResult::class => ResetResult::fromArray($data),
            default => throw new \RuntimeException('Unsupported result type.'),
        };
    }
}
