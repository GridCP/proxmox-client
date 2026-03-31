<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Parameters\Qemu;

use GridCP\Proxmox\Api\Parameters\ParametersInterface;

final class CreateVirtualMachineParameters implements ParametersInterface
{
    /**
     * @param array<string, scalar> $parameters
     */
    public function __construct(private array $parameters = [])
    {
    }

    public function vmId(int $vmId): self
    {
        $this->parameters['vmid'] = $vmId;

        return $this;
    }

    public function name(string $name): self
    {
        $this->parameters['name'] = $name;

        return $this;
    }

    public function cores(int $cores): self
    {
        $this->parameters['cores'] = $cores;

        return $this;
    }

    public function onBoot(bool $onBoot): self
    {
        $this->parameters['onboot'] = $onBoot;

        return $this;
    }

    public function agent(string $agent): self
    {
        $this->parameters['agent'] = $agent;

        return $this;
    }

    public function scsihw(string $scsihw): self
    {
        $this->parameters['scsihw'] = $scsihw;

        return $this;
    }

    public function net0(string $net0): self
    {
        $this->parameters['net0'] = $net0;

        return $this;
    }

    public function tags(string $tags): self
    {
        $this->parameters['tags'] = $tags;

        return $this;
    }

    public function boot(string $boot): self
    {
        $this->parameters['boot'] = $boot;

        return $this;
    }

    public function cpu(string $cpu): self
    {
        $this->parameters['cpu'] = $cpu;

        return $this;
    }

    public function memory(int $memory): self
    {
        $this->parameters['memory'] = $memory;

        return $this;
    }

    public function balloon(bool $balloon): self
    {
        $this->parameters['balloon'] = $balloon;

        return $this;
    }

    public function ide2(string $ide2): self
    {
        $this->parameters['ide2'] = $ide2;

        return $this;
    }

    public function ciuser(string $ciUser): self
    {
        $this->parameters['ciuser'] = $ciUser;

        return $this;
    }

    public function cipassword(string $ciPassword): self
    {
        $this->parameters['cipassword'] = $ciPassword;

        return $this;
    }

    public function scsi0(string $scsi0): self
    {
        $this->parameters['scsi0'] = $scsi0;

        return $this;
    }

    public function osType(string $osType): self
    {
        $this->parameters['ostype'] = $osType;

        return $this;
    }

    public function bios(string $bios): self
    {
        $this->parameters['bios'] = $bios;

        return $this;
    }

    public function machine(string $machine): self
    {
        $this->parameters['machine'] = $machine;

        return $this;
    }

    public function efiDisk0(string $efiDisk0): self
    {
        $this->parameters['efidisk0'] = $efiDisk0;

        return $this;
    }

    public function tpmState0(string $tpmState0): self
    {
        $this->parameters['tpmstate0'] = $tpmState0;

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
