<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Currrent\Domain\Reponses;

final readonly  class CurrrentResponse
{

    public function __construct(private array $current  )
    {
    }

    public function get():array
    {
        return $this->current;
    }
}