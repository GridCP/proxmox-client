<?php
declare(strict_types=1);

namespace GridCP\Proxmox_Client\Cluster\App;


use GridCP\Proxmox_Client\Cluster\Domain\Exceptions\ClusterNotFound;
use GridCP\Proxmox_Client\Cluster\Domain\Responses\ClusterResponse;
use GridCP\Proxmox_Client\Cluster\Domain\Responses\NodesCluster;
use GridCP\Proxmox_Client\Cluster\Domain\Responses\NodesClusterResponse;
use GridCP\Proxmox_Client\Commons\Application\Helpers\GFunctions;
use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\AuthFailedException;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\HostUnreachableException;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GuzzleHttp\Exception\GuzzleException;

final class GetClusterStatus extends GClientBase
{
    use GFunctions;

    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)
    {
        parent::__construct($connection, $cookiesPVE);
    }

    public function __invoke()
    {
        try {
            $result = $this->Get("cluster/status", []);
            if (empty($result)) throw new ClusterNotFound();
            $cluster = $result[0];
            $nodeCluster = array_slice($result, 0);
            return new ClusterResponse(
              (array_key_exists('type', $cluster))?$cluster['type']:null,
              (array_key_exists('name', $cluster))?$cluster['name']:null,
              (array_key_exists('version', $cluster))?$cluster['version']:null,
              (array_key_exists('id', $cluster))?$cluster['id']:null,
              new NodesClusterResponse( ...array_map($this->toResponse(),$nodeCluster))
           );
        }catch (GuzzleException $ex){
            if ($ex->getCode() === 401) throw new AuthFailedException();
            if ($ex->getCode() === 0) throw new HostUnreachableException();
        }

    }
    public function toResponse():callable
    {
      return static fn($result): NodesCluster=>new NodesCluster(
        (array_key_exists('name', $result))?$result['name']:null,
        (array_key_exists('ip', $result))?$result['ip']:null,
        (array_key_exists('type', $result)) ?$result['type']:null,
        (array_key_exists('level', $result)) ?$result['level']:null,
        (array_key_exists('nodeId', $result)) ?$result['nodeId']:false,
        (array_key_exists('local', $result)) ?$result['local']:null,
        (array_key_exists('online', $result)) ?$result['online']:null,
        (array_key_exists('id', $result)) ?$result['id']:null,
        );
    }


}