<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result;

use GridCP\Proxmox\Exception\AuthenticationException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ResultConverter implements ResultConverterInterface
{
    private readonly Serializer $serializer;

    public function __construct(?Serializer $serializer = null)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
        $nameConverter = new MetadataAwareNameConverter($classMetadataFactory);

        $normalizers = [
            new ArrayDenormalizer(),
            new ObjectNormalizer($classMetadataFactory, $nameConverter),
        ];

        $this->serializer = $serializer ?? new Serializer($normalizers);
    }

    public function convert(
        ResponseInterface $response,
        string $resultType,
        array $options = [],
    ): ResultInterface|array {
        $statusCode = $response->getStatusCode();

        /* Not implemented */
        if (501 === $statusCode) {
            throw new \RuntimeException($this->resolveErrorMessage($response));
        }

        /* Internal server error */
        if (500 === $statusCode) {
            throw new \RuntimeException($this->resolveErrorMessage($response));
        }

        /* Bad request */
        if (400 === $statusCode) {
            throw new \RuntimeException($this->resolveErrorMessage($response));
        }

        /* Unauthorized */
        if (401 == $statusCode) {
            throw new AuthenticationException($this->resolveErrorMessage($response));
        }

        /* Forbidden */
        if (403 === $statusCode) {
            throw new \RuntimeException($this->resolveErrorMessage($response));
        }

        /* Network is unreachable */
        if (595 === $statusCode) {
            throw new \RuntimeException($this->resolveErrorMessage($response));
        }

        $content = $response->getBody()->getContents();
        $data = json_decode($content, true);

        return $this->normalize($resultType, $data);
    }

    private function normalize(string $resultType, array $data): ResultInterface|array
    {
        $data = $data['data'] ?? [];
        $payload = $data;

        if (false === is_array($data)) {
            $payload = [
                'upid' => $data,
            ];
        }

        return $this->serializer->denormalize($payload, $resultType);
    }

    private function resolveErrorMessage(ResponseInterface $response): string
    {
        $content = $response->getBody()->getContents();

        /**
         * @var array{
         *     data: null,
         *     message: string,
         *     errors?: array<string, string>
         * } $data
         */
        $data = json_decode($content, true);
        $message = $data['message'] ?? null;

        if (is_string($message) && '' !== trim($message)) {
            return trim($message);
        }

        $reasonPhrase = trim($response->getReasonPhrase());
        if ('' !== $reasonPhrase) {
            return $reasonPhrase;
        }

        return sprintf('HTTP %d error', $response->getStatusCode());
    }
}
