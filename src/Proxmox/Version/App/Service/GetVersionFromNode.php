<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Proxmox\Version\App\Service;


use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\AuthFailedException;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\HostUnreachableException;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\Proxmox\Version\Domain\Exceptions\VersionError;
use GridCP\Proxmox_Client\Proxmox\Version\Domain\Responses\VersionResponse;
use GuzzleHttp\Exception\GuzzleException;

final class GetVersionFromNode extends GClientBase
{
    /**
     * @param Connection $connection
     * @param CookiesPVE $cookiesPVE
     */
    public  function  __construct(Connection $connection, CookiesPVE $cookiesPVE)
    {
        parent::__construct($connection, $cookiesPVE);
    }

    /**
     * @return VersionResponse|null
     */
    public function __invoke():?VersionResponse
    {
        try {
            $result = $this->Get("version", []);
            if(empty($result)) throw new VersionError("Error in get Version");
            return  $this->toResponse($result);
        }catch (GuzzleException $ex){
            if ($ex->getCode() === 401) throw new AuthFailedException();
            if ($ex->getCode() === 0) throw new HostUnreachableException();
        }
        return null;
    }

    /**
     * @param $result
     * @return VersionResponse
     */
    public function toResponse($result):VersionResponse
    {
        return  new VersionResponse(
            (array_key_exists('release', $result))?$result['release']:"",
            (array_key_exists('repoid', $result))?$result['repoid']:"",
            (array_key_exists('version', $result))?$result['version']:"",
        );
    }
}