<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Parameters;

final class ConfigureParameters implements ParametersInterface
{
    /**
     * @param array<string, scalar> $parameters
     */
    public function __construct(private array $parameters = [])
    {
    }

    public function parameter(string $name, string|int|float|bool $value): self
    {
        $this->parameters[$name] = $value;

        return $this;
    }

    /**
     * Keyboard layout for VNC server. This option is generally not required and is often better
     * handled from within the guest OS.
     */
    public function keyboard(string $keyboard): self
    {
        $this->parameters['keyboard'] = $keyboard;

        return $this;
    }

    public function localtime(bool $localtime): self
    {
        $this->parameters['localtime'] = $localtime;

        return $this;
    }

    /**
     * Memory properties.
     */
    public function memory(int $memory): self
    {
        $this->parameters['memory'] = $memory;

        return $this;
    }

    public function cores(int $cores): self
    {
        $this->parameters['cores'] = $cores;

        return $this;
    }

    public function name(string $name): self
    {
        $this->parameters['name'] = $name;

        return $this;
    }

    public function machine(string $machine): self
    {
        $this->parameters['machine'] = $machine;

        return $this;
    }

    public function onboot(bool $onboot): self
    {
        $this->parameters['onboot'] = $onboot;

        return $this;
    }

    public function ostype(string $ostype): self
    {
        $this->parameters['ostype'] = $ostype;

        return $this;
    }

    public function bios(string $bios): self
    {
        $this->parameters['bios'] = $bios;

        return $this;
    }

    public function ciuser(string $ciuser): self
    {
        $this->parameters['ciuser'] = $ciuser;

        return $this;
    }

    public function cipassword(string $cipassword): self
    {
        $this->parameters['cipassword'] = $cipassword;

        return $this;
    }

    public function net(int $index, string $net): self
    {
        $this->parameters[sprintf('net%d', $index)] = $net;

        return $this;
    }

    public function net0(string $net): self
    {
        return $this->net(0, $net);
    }

    public function ipconfig(int $index, string $ipConfig): self
    {
        $this->parameters[sprintf('ipconfig%d', $index)] = $ipConfig;

        return $this;
    }

    public function ipconfig0(string $ipConfig): self
    {
        return $this->ipconfig(0, $ipConfig);
    }

    public function digest(string $digest): self
    {
        $this->parameters['digest'] = $digest;

        return $this;
    }

    public function delete(string $delete): self
    {
        $this->parameters['delete'] = $delete;

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
