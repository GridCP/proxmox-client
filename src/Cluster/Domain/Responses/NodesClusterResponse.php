<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Cluster\Domain\Responses;

final class NodesClusterResponse
{
    private array $nodes;

    public function __construct(NodesCluster ...$nodes )
    {
        $this->nodes = $nodes;
    }

    public  function nodes():array
    {
        return  $this->nodes;
    }
}