<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Api\Parameters;

class DestroyParameters implements ParametersInterface
{
    /**
     * @param array<string, scalar> $parameters
     */
    public function __construct(private array $parameters = [])
    {
    }

    /**
     * If set, destroy additionally all disks not referenced in the config but with a matching VMID from all enabled storages.
     */
    public function destroyUnreferencedDisks(bool $destroyUnreferencedDisks): self
    {
        $this->parameters['destroy-unreferenced-disks'] = $destroyUnreferencedDisks;

        return $this;
    }

    /**
     * Remove VMID from configurations, like backup and replication jobs and HA.
     */
    public function purge(bool $purge): self
    {
        $this->parameters['purge'] = $purge;

        return $this;
    }

    /**
     * Ignore locks - only root is allowed to use this option.
     */
    public function skipLock(bool $skipLock): self
    {
        $this->parameters['skiplock'] = $skipLock;

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
