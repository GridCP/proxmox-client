<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Api\Parameters;

class StopParameters implements ParametersInterface
{
    /**
     * @param array<string, scalar> $parameters
     */
    public function __construct(private array $parameters = [])
    {
    }

    public function keepActive(bool $keepActive): self
    {
        $this->parameters['keepActive'] = $keepActive;

        return $this;
    }

    public function migratedFrom(string $migratedFrom): self
    {
        $this->parameters['migratedfrom'] = $migratedFrom;

        return $this;
    }

    public function overruleShutdown(bool $overruleShutdown): self
    {
        $this->parameters['overrule-shutdown'] = $overruleShutdown;

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
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->parameters;
    }
}
