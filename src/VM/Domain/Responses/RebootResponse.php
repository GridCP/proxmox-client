<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\VM\Domain\Responses;

final readonly  class RebootResponse
{

    public function __construct(private array $current  )
    {
    }

    public function get():array
    {
        return $this->current;
    }
}
