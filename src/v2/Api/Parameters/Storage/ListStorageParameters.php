<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Parameters\Storage;

use GridCP\Proxmox\Api\Parameters\ParametersInterface;

class ListStorageParameters implements ParametersInterface
{
    /**
     * @param array<string, scalar> $parameters
     */
    public function __construct(private array $parameters = [])
    {
    }

    /**
     * Only list stores which support this content type.
     */
    public function content(string $content): self
    {
        $this->parameters['content'] = $content;

        return $this;
    }

    /**
     * Only list stores which are enabled (not disabled in config).
     */
    public function enabled(bool $enabled): self
    {
        $this->parameters['enabled'] = $enabled;

        return $this;
    }

    /**
     * Include information about formats.
     */
    public function format(bool $format): self
    {
        $this->parameters['format'] = $format;

        return $this;
    }

    /**
     * Only list status for specified storage.
     */
    public function storage(string $storage): self
    {
        $this->parameters['storage'] = $storage;

        return $this;
    }

    /**
     * If target is different to node, only shared storages accessible on both are listed.
     */
    public function target(string $target): self
    {
        $this->parameters['target'] = $target;

        return $this;
    }

    /**
     * @return array<string, scalar>
     */
    public function toArray(): array
    {
        return $this->parameters;
    }
}
