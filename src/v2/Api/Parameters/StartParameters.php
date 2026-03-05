<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Api\Parameters;

final class StartParameters
{
    /**
     * @param array<string, scalar> $parameters
     */
    public function __construct(private array $parameters = [])
    {
    }

    public function forceCpu(string $value): self
    {
        $this->parameters['force-cpu'] = $value;

        return $this;
    }

    public function machine(string $value): self
    {
        $this->parameters['machine'] = $value;

        return $this;
    }

    public function migratedFrom(string $migratedFrom): self
    {
        $this->parameters['migratedfrom'] = $migratedFrom;

        return $this;
    }

    public function migrationNetwork(string $migrationNetwork): self
    {
        $this->parameters['migration_network'] = $migrationNetwork;

        return $this;
    }

    public function migrationType(MigrationType $migrationType): self
    {
        $this->parameters['migration_type'] = $migrationType;

        return $this;
    }

    public function netsHostMtu(string $value): self
    {
        $this->parameters['nets-host-mtu'] = $value;

        return $this;
    }

    public function skipLock(bool $skipLock): self
    {
        $this->parameters['skiplock'] = $skipLock;

        return $this;
    }

    public function stateUri(string $stateUri): self
    {
        $this->parameters['stateuri'] = $stateUri;

        return $this;
    }

    public function targetStorage(string $targetStorage): self
    {
        $this->parameters['targetstorage'] = $targetStorage;

        return $this;
    }

    public function timeout(int $timeout): self
    {
        $this->parameters['timeout'] = $timeout;

        return $this;
    }

    public function withConntrackState(bool $withConntrackState): self
    {
        $this->parameters['with-conntrack-state'] = $withConntrackState;

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
