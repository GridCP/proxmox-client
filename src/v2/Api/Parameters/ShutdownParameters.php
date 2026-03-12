<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Parameters;

final class ShutdownParameters implements ParametersInterface
{
    /**
     * @param array<string, scalar> $parameters
     */
    public function __construct(private array $parameters = [])
    {
    }

    public function forceStop(bool $forceStop): self
    {
        $this->parameters['forceStop'] = $forceStop;

        return $this;
    }

    public function keepActive(bool $keepActive): self
    {
        $this->parameters['keepActive'] = $keepActive;

        return $this;
    }

    public function skipLock(bool $skipLock): self
    {
        $this->parameters['skiplock'] = $skipLock;

        return $this;
    }

    public function timeout(int $timeout): self
    {
        $this->parameters['timeout'] = $timeout;

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
