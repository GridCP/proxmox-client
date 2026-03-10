<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Parameters;

class RebootParameters implements ParametersInterface
{
    /**
     * @param array<string, scalar> $parameters
     */
    public function __construct(private array $parameters = [])
    {
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
