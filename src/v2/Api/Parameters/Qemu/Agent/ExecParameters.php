<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Parameters\Qemu\Agent;

use GridCP\Proxmox\Api\Parameters\ParametersInterface;

class ExecParameters implements ParametersInterface
{
    /**
     * @param array<string, scalar> $parameters
     */
    public function __construct(private array $parameters = [])
    {
    }

    public function command(string $command): self
    {
        $this->parameters['command'] = $command;

        return $this;
    }

    public function inputData(string $inputData): self
    {
        $this->parameters['input-data'] = $inputData;

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
