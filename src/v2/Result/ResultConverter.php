<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result;

use GridCP\Proxmox\Exception\AuthenticationException;
use GridCP\Proxmox\Result\Normalizer\ApiResultDenormalizer;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Serializer;

class ResultConverter implements ResultConverterInterface
{
    private readonly Serializer $serializer;

    public function __construct(?Serializer $serializer = null)
    {
        $this->serializer = $serializer ?? new Serializer([new ApiResultDenormalizer()]);
    }

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
        $result = $this->serializer->denormalize($data, $resultType);
        if (false === $result instanceof ResultInterface) {
            throw new \RuntimeException('Unsupported result type.');
        }

        return $result;
    }
}
