<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Parameters\Qemu;

use GridCP\Proxmox\Api\Parameters\ParametersInterface;

class DestroyVirtualMachineParameters implements ParametersInterface
{
    public function __construct(private array $parameters = [])
    {
    }

    public function destroyUnreferencedDisks(bool $destroyUnreferencedDisks): self
    {
        $this->parameters['destroy-unreferenced-disks'] = $destroyUnreferencedDisks;

        return $this;
    }

    public function purge(bool $purge): self
    {
        $this->parameters['purge'] = $purge;

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
