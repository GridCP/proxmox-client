<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Result;

use GridCP\Proxmox\Api\Exception\AuthenticationException;
use Psr\Http\Message\ResponseInterface;

class ResultConverter implements ResultConverterInterface
{
    public function convert(
        ResponseInterface $response,
        string $resultType,
        array $options = [],
    ): ResultInterface {
        $content = $response->getBody()->getContents();

        if (501 === $response->getStatusCode()) {
            /**
             * @var array{
             *     data: null,
             *     message: string,
             * } $data
             */
            $data = json_decode($content, true);
            throw new \RuntimeException($data['message']);
        }

        if (500 === $response->getStatusCode()) {
            /**
             * @var array{
             *     data: null,
             *     message: string,
             * } $data
             */
            $data = json_decode($content, true);
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
            $data = json_decode($content, true);
            throw new \RuntimeException($data['message']);
        }

        if (401 == $response->getStatusCode()) {
            /**
             * @var array{
             *     data: null,
             *     message: string,
             * } $data
             */
            $data = json_decode($content, true);
            throw new AuthenticationException($data['message']);
        }

        $data = json_decode($content, true);

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
            StartResult::class => StartResult::fromArray($data),
            CurrentResult::class => CurrentResult::fromArray($data),
            StatusResult::class => StatusResult::fromArray($data),
            default => throw new \RuntimeException('Unsupported result type.'),
        };
    }
}
