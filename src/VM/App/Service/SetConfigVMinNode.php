<?php

declare(strict_types=1);

namespace GridCP\Proxmox_Client\VM\App\Service;



use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\GetConfigVMException;

class SetConfigVMinNode extends GClientBase

{

    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)

    {

        parent::__construct($connection, $cookiesPVE);

    }



    public function __invoke(string $node, int $vmid, array $params)

    {
        $body = [];
        $keys_to_check = ['keyboard', 'localtime', 'memory', 'name', 'machine', 'onboot', 'ostype', 'bios'];
        $filtered_params = array_intersect_key($params, array_flip($keys_to_check));
        $body = array_merge($body, $filtered_params);
        try{



          $result =  $this->Post("nodes/".$node."/qemu/".$vmid."/config", $body);

          return $result;

        }catch(\Exception $ex){
            if ($ex->getCode()===500) throw new GetConfigVMException($ex->getMessage());
            return throw new GetConfigVMException("Error in Config VM");



        }

        return null;

    }





}