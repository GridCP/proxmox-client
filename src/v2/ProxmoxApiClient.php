<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

use GridCP\Proxmox\Api\Result\RawHttpResult;
use Psr\Http\Client\ClientInterface;

class ProxmoxApiClient
{
    private string $ticket;
    private string $csrfToken;

    public function __construct(
        private readonly ClientInterface $httpClient,
    ) {
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    public function login(
        string $realm,
        string $username,
        string $password,
    ): self {
        $response = $this->httpClient->request(
            'POST',
            '/api2/json/access/ticket',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'username' => $username,
                    'password' => $password,
                    'realm' => $realm,
                ],
            ]
        );

        /**
         * @var array{
         *     data: array{
         *         cap: array{
         *             access: array{
         *                 "Group.Allocate": int,
         *                 "Permissions.Modify": int,
         *                 "User.Modify": int
         *             },
         *             storage: array{
         *                 "Permissions.Modify": int,
         *                 "Datastore.AllocateSpace": int,
         *                 "Datastore.Allocate": int,
         *                 "Datastore.Audit": int,
         *                 "Datastore.AllocateTemplate": int
         *             },
         *             sdn: array{
         *                 "SDN.Audit": int,
         *                 "Permissions.Modify": int,
         *                 "SDN.Allocate": int,
         *                 "SDN.Use": int
         *             },
         *             vms: array{
         *                 "VM.Snapshot.Rollback": int,
         *                 "VM.Migrate": int,
         *                 "VM.Config.Cloudinit": int,
         *                 "VM.Config.Options": int,
         *                 "VM.Config.CDROM": int,
         *                 "VM.Audit": int,
         *                 "VM.Backup": int,
         *                 "VM.Allocate": int,
         *                 "VM.Config.Disk": int,
         *                 "VM.Monitor": int,
         *                 "VM.Clone": int,
         *                 "VM.Config.Network": int,
         *                 "VM.PowerMgmt": int,
         *                 "VM.Config.Memory": int,
         *                 "VM.Snapshot": int,
         *                 "Permissions.Modify": int,
         *                 "VM.Config.CPU": int,
         *                 "VM.Config.HWType": int,
         *                 "VM.Console": int
         *             },
         *             dc: array{
         *                 "SDN.Use": int,
         *                 "SDN.Allocate": int,
         *                 "Sys.Audit": int,
         *                 "SDN.Audit": int,
         *                 "Sys.Modify": int
         *             },
         *             mapping: array{
         *                 "Mapping.Audit": int,
         *                 "Mapping.Use": int,
         *                 "Mapping.Modify": int,
         *                 "Permissions.Modify": int
         *             },
         *             nodes: array{
         *                 "Sys.PowerMgmt": int,
         *                 "Sys.Audit": int,
         *                 "Sys.Modify": int,
         *                 "Sys.Syslog": int,
         *                 "Sys.AccessNetwork": int,
         *                 "Permissions.Modify": int,
         *                 "Sys.Incoming": int,
         *                 "Sys.Console": int
         *             }
         *         },
         *         ticket: string,
         *         CSRFPreventionToken: string,
         *         username: string
         *     }
         * } $data
         */
        $data = $response->toArray();
        $this->ticket = $data['data']['ticket'];
        $this->csrfToken = $data['data']['CSRFPreventionToken'];

        return $this;
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function request(string $method, string $url, array $options = []): RawHttpResult
    {
        $headers = [
            'Cookie' => "PVEAuthCookie=$this->ticket",
            'CSRFPreventionToken' => $this->csrfToken,
        ];
        $options['headers'] = array_merge($headers, $options['headers'] ?? []);

        $result = $this->httpClient->request($method, $url, $options);

        return new RawHttpResult($result);
    }

    public function nodes(string $node): NodeApi
    {
        return new NodeApi($this, $node);
    }
}
