<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\VM\App\Service;



use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\PostRequestException;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\VncProxyError;
use GridCP\Proxmox_Client\VM\Domain\Responses\VncResponse;

final class  CreateVncProxy extends GClientBase
{
    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)
    {
        parent::__construct($connection, $cookiesPVE);
    }


    public function __invoke(string $node, int $vmid):?VncResponse
    {
        try {
            $body=[
            "generate-password"=>true,
             "websocket"=>true
            ];
            $result = $this->Post("nodes/" . $node . "/qemu/" . $vmid.'/vncproxy',$body);
            return $this->toResponse(json_decode($result->getBody()->getContents(),true));
        }catch (PostRequestException $e ){
                if ($e->getCode()===500) throw new VncProxyError($e->getMessage());
                return throw new VncProxyError("Error in create VM ->".$e->getMessage());

        }
    }

    public function toResponse($result):VncResponse
    {
        return new VncResponse(
            $result['data']['password'],
            $result['data']['cert'],
            $result ['data']['upid'],
            $result['data']['port'],
            $result['data']['user'],
            $result['data']['ticket']
        );
    }
}