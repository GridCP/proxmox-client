<?php

declare(strict_types=1);

namespace GridCP\Proxmox_Client\Commons\Application\Helpers;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Psr7\Response;

trait GFunctions
{
    /**
     * @return array{
     *     ticket: string,
     *     username: string,
     *     CSRFPreventionToken: string,
     *     cap: array{
     *         nodes?: array{
     *             Sys.Audit?: int,
     *             Sys.Modify?: int,
     *             Sys.Syslog?: int,
     *             Sys.PowerMgmt?: int,
     *             Sys.Console?: int,
     *             Permissions.Modify?: int,
     *             Sys.Incoming?: int,
     *             Sys.AccessNetwork?: int
     *         },
     *         mapping?: array{
     *             Mapping.Modify?: int,
     *             Permissions.Modify?: int,
     *             Mapping.Use?: int,
     *             Mapping.Audit?: int
     *         },
     *         dc?: array{
     *             Sys.Audit?: int,
     *             SDN.Audit?: int,
     *             Sys.Modify?: int,
     *             SDN.Allocate?: int,
     *             SDN.Use?: int
     *         },
     *         vms?: array{
     *             VM.Config.Options?: int,
     *             VM.Config.CDROM?: int,
     *             VM.Config.Cloudinit?: int,
     *             VM.Migrate?: int,
     *             VM.Snapshot.Rollback?: int,
     *             VM.Backup?: int,
     *             VM.Audit?: int,
     *             VM.Monitor?: int,
     *             VM.Allocate?: int,
     *             VM.Config.Disk?: int,
     *             VM.Clone?: int,
     *             VM.Config.Network?: int,
     *             VM.PowerMgmt?: int,
     *             Permissions.Modify?: int,
     *             VM.Snapshot?: int,
     *             VM.Config.Memory?: int,
     *             VM.Config.HWType?: int,
     *             VM.Config.CPU?: int,
     *             VM.Console?: int
     *         },
     *         storage?: array{
     *             Datastore.Allocate?: int,
     *             Permissions.Modify?: int,
     *             Datastore.AllocateSpace?: int,
     *             Datastore.AllocateTemplate?: int,
     *             Datastore.Audit?: int
     *         },
     *         access?: array{
     *             Group.Allocate?: int,
     *             Permissions.Modify?: int,
     *             User.Modify?: int
     *         },
     *         sdn?: array{
     *             Permissions.Modify?: int,
     *             SDN.Allocate?: int,
     *             SDN.Use?: int,
     *             SDN.Audit?: int
     *         }
     *     }
     * }
     */
    public function decodeBody(Response $data): array
    {
        return json_decode($data->getBody()->getContents(), true)['data'];
    }

    public function getCookies(string $ticket, string $host): CookieJar
    {
        return CookieJar::fromArray(['PVEAuthCookie' => $ticket], $host);
    }
}
