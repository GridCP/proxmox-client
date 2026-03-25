<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Parameters;

final class ResizeParameters implements ParametersInterface
{
    /**
     * @param array<string, scalar> $parameters
     */
    public function __construct(private array $parameters = [])
    {
    }

    public function disk(int $index, string $disk): self
    {
        $this->parameters['disk'] = sprintf('%s%d', $disk, $index);

        return $this;
    }

    public function size(string $size): self
    {
        $this->parameters['size'] = $size;

        return $this;
    }

    public function digest(string $digest): self
    {
        $this->parameters['digest'] = $digest;

        return $this;
    }

    public function skipLock(bool $skipLock): self
    {
        $this->parameters['skiplock'] = $skipLock;

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
