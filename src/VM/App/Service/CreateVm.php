<?php

declare(strict_types=1);

namespace GridCP\Proxmox_Client\VM\App\Service;




use GridCP\Proxmox_Client\Commons\Application\Helpers\GFunctions;
use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\NotFoundSOException;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\PostRequestException;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\VM\App\Service\Help\So\SoVm;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\VmErrorCreate;
use GridCP\Proxmox_Client\VM\Domain\Responses\VmResponse;
use GridCP\Proxmox_Client\VM\Domain\Responses\VmsResponse;
use GridCP\Proxmox_Client\VM\Infrastructure\Symfony\Events\CreateVMDispatcher;
use GridCP\Proxmox_Client\VM\Infrastructure\Symfony\Events\CreateVMEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CreateVm extends  GClientBase
{

    use GFunctions;

    public function __construct(Connection $connection,
                                CookiesPVE $cookiesPVE,
                                private readonly  CreateVMDispatcher $eventDispatcher)
    {
        parent::__construct($connection, $cookiesPVE);
    }


    
    public function __invoke(
                                string  $nodeName, int $vmId, ?int $vmCpuCores, ?string $vmName, ?int $vmNetId,
                                ?string $vmNetModel, ?string $vmNetBridge, ?int $vmNetFirewall, ?bool $vmOnBoot,
                                ?string $vmScsiHw, ?string $vmDiskType, ?int    $vmDiskId, ?string $vmDiskStorage,
                                ?string $vmDiskDiscard, ?string $vmDiskCache, ?string $vmDiskImportFrom, ?string $vmTags,
                                ?int    $vmCloudInitIdeId, ?string $vmCloudInitStorage, ?string $vmBootOrder, ?int $vmAgent,
                                ?int    $vmNetNetId, ?string $vmNetIp, ?string $vmNetGw, ?string $vmOsUserName,
                                ?string $vmOsPassword, ?string $vmCpuType, ?int $vmMemory = null, ?int $vmMemoryBallon = null,
                                ?string $vmOsType = null,?string $vmBios = null,?string $vmMachinePc = null,
                                ?string $vmEfiStorage = null, ?int $vmEfiKey = null,
                                ?string $efidiskNvme = null, ?string $efidiskEnrroled = null,
                                ?string $tpmstateNvme = null, ?string $tpmstateVersion = null,
                                ?string $soBuild = 'Debian12'
                            ): VmsResponse

    {
        $soClass = SoVm::get($soBuild);
        if ( $soClass instanceof NotFoundSOException) {
            return new NotFoundSOException($soBuild);
        }
        $so = new $soClass(
                            $nodeName, $vmId, $vmCpuCores, $vmName, $vmNetId,
                            $vmNetModel, $vmNetBridge, $vmNetFirewall, $vmOnBoot,
                            $vmScsiHw, $vmDiskType, $vmDiskId, $vmDiskStorage,
                            $vmDiskDiscard, $vmDiskCache, $vmDiskImportFrom, $vmTags,
                            $vmCloudInitIdeId, $vmCloudInitStorage, $vmBootOrder, $vmAgent,
                            $vmNetNetId, $vmNetIp, $vmNetGw, $vmOsUserName,
                            $vmOsPassword, $vmCpuType, $vmMemory, $vmMemoryBallon,
                            $vmOsType, $vmBios,$vmMachinePc,
                            $vmEfiStorage, $vmEfiKey,
                            $efidiskNvme, $efidiskEnrroled,
                            $tpmstateNvme, $tpmstateVersion
                          );
            
            
            $body = $so->buildData();

            try {
                $result = $this->Post("nodes/".$nodeName."/qemu/", $body);
                $getContent = json_decode($result->getBody()->getContents());
                $vmResponses = array_map($this->toResponse(), (array)$getContent);
                $vmResponsesNumeric = array_values($vmResponses);
                $this->eventDispatcher->execute();
                return new VmsResponse(...$vmResponsesNumeric);

            }catch (PostRequestException $e ){
                if ($e->getCode()===500) {
                    throw new VmErrorCreate($e->getMessage());
                }
                throw new VmErrorCreate("Error in create VM");
            }
    }



    public function toResponse():callable
    {
        return static fn($result):VmResponse=>new VmResponse(
            $result
        );
    }

}
