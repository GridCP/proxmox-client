<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Result;

use GridCP\Proxmox\Exception\AuthenticationException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
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
            new ObjectNormalizer($classMetadataFactory, $nameConverter),
        ];

        $this->serializer = $serializer ?? new Serializer($normalizers);
    }

    public function convert(
        ResponseInterface $response,
        string $resultType,
        array $options = [],
    ): ResultInterface {
        $content = $response->getBody()->getContents();

        /* Not implemented */
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

        /* Internal server error */
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

        /* Bad request */
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

        /* Unauthorized */
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
        $data = is_array($data['data'] ?? null) ? $data['data'] : [];

        $result = $this->serializer->denormalize($data, $resultType);
        if (false === $result instanceof ResultInterface) {
            throw new \RuntimeException('Unsupported result type.');
        }

        return $result;
    }
}
