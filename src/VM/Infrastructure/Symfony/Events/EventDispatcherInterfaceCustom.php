<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\VM\Infrastructure\Symfony\Events;

interface EventDispatcherInterfaceCustom
{
    public function dispatch(object $event, string $eventName = null): object;
}