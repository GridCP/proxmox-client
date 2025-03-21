<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\VM\Infrastructure\Symfony\Events;
use Symfony\Contracts\EventDispatcher\Event;

class CreateVMEvent extends Event
{
    public const NAME = 'proxmox_client.create_vm_event';

    private string $message;


    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}