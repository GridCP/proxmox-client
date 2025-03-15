<?php

declare(strict_types=1);

namespace GridCP\Proxmox_Client\VM\Domain\Exceptions;



final class AgentExecVMException extends \Exception

{

    public function __construct(string $message)

    {

        parent::__construct("Error Agent Exec ->".$message, 400);

    }

}