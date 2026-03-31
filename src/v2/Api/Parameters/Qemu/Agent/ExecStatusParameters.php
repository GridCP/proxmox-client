<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Parameters\Qemu\Agent;

use GridCP\Proxmox\Api\Parameters\ParametersInterface;

class ExecStatusParameters implements ParametersInterface
{
    /**
     * @param array<string, scalar> $parameters
     */
    public function __construct(private array $parameters = [])
    {
    }

    public function pid(int $pid): self
    {
        $this->parameters['pid'] = $pid;

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
