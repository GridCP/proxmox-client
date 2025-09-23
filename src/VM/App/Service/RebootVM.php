<?php

declare(strict_types=1);

namespace GridCP\Proxmox_Client\VM\App\Service;

use GridCP\Proxmox_Client\Commons\Application\Helpers\GFunctions;
use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\AuthFailedException;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\HostUnreachableException;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\PostRequestException;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\RebootNotFound;

final class RebootVM extends GClientBase
{
    use GFunctions;

    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)
    {
        parent::__construct($connection, $cookiesPVE);
    }

    /**
     * @throws AuthFailedException
     * @throws HostUnreachableException
     * @throws RebootNotFound
     */
    public function __invoke(string $node, int $vmid): string
    {
        try {
            $body = [
                'timeout' => 10,
            ];
            $result = $this->Post('nodes/'.$node.'/qemu/'.$vmid.'/status/reboot', $body);

            return $result->getBody()->getContents();
        } catch (PostRequestException $e) {
            if (500 === $e->getCode()) {
                throw new RebootNotFound();
            }

            throw new RebootNotFound();
        }
    }
}
