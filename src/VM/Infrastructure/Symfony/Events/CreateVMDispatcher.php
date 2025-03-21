<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\VM\Infrastructure\Symfony\Events;

use Psr\EventDispatcher\EventDispatcherInterface;

class CreateVMDispatcher
{

    public function __construct( private  readonly EventDispatcherInterfaceCustom $eventDispatcher){}

    public function execute():bool{
        $createVMEvent = new CreateVMEvent("prueba");
        $this->eventDispatcher->dispatch($createVMEvent, $createVMEvent::NAME);
        return true;
    }

}