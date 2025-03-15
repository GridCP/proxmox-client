<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\VM\App\Service;


use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\GetRequestException;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\VncWebSocketError;

final class CreateVncWebSocket extends GClientBase
{
    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)
    {
        parent::__construct($connection, $cookiesPVE);
    }

    public function __invoke(string $node, int $vmid, int $port, string $vncTicket)
    {

        try {
            $params = [
                'port' => $port,
                'vncticket' => $vncTicket
            ];
            return $this->Get("nodes/" . $node . "/qemu/" . $vmid . "/vncwebsocket", $params);
        }catch(GetRequestException $e){
            if ($e->getCode()===500) throw new VncWebSocketError( $e->getMessage());
            return throw new VncWebSocketError("Error in create VncWebSocketError ->".$e->getMessage());
        }
    }


}