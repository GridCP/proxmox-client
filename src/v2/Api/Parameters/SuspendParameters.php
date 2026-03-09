<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Parameters;

final class SuspendParameters implements ParametersInterface
{
    /**
     * @param array<string, scalar> $parameters
     */
    public function __construct(private array $parameters = [])
    {
    }

    public function skipLock(bool $skipLock): self
    {
        $this->parameters['skiplock'] = $skipLock;

        return $this;
    }

    public function stateStorage(string $stateStorage): self
    {
        $this->parameters['statestorage'] = $stateStorage;

        return $this;
    }

    public function toDisk(bool $toDisk): self
    {
        $this->parameters['todisk'] = $toDisk;

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
