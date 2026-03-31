<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Parameters\Qemu;

use GridCP\Proxmox\Api\Parameters\ParametersInterface;

final class WriteFileParameters implements ParametersInterface
{
    /**
     * @param array<string, scalar> $parameters
     */
    public function __construct(private array $parameters = [])
    {
    }

    public function content(string $content): self
    {
        $this->parameters['content'] = $content;

        return $this;
    }

    public function file(string $file): self
    {
        $this->parameters['file'] = $file;

        return $this;
    }

    public function encode(string $encode): self
    {
        $this->parameters['encode'] = $encode;

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
