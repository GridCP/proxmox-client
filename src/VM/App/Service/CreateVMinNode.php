<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\VM\App\Service;

use GridCP\Proxmox_Client\Commons\Application\Helpers\GFunctions;
use GridCP\Proxmox_Client\Commons\Domain\Exceptions\PostRequestException;
use GridCP\Proxmox_Client\Commons\infrastructure\GClientBase;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\VmErrorCreate;
use GridCP\Proxmox_Client\VM\Domain\Model\CpuModel;
use GridCP\Proxmox_Client\VM\Domain\Model\EfiModel;
use GridCP\Proxmox_Client\VM\Domain\Model\IpModel;
use GridCP\Proxmox_Client\VM\Domain\Model\NetModel;
use GridCP\Proxmox_Client\VM\Domain\Model\Storage\IdeModel;
use GridCP\Proxmox_Client\VM\Domain\Model\Storage\SataModel;
use GridCP\Proxmox_Client\VM\Domain\Model\Storage\ScsiModel;
use GridCP\Proxmox_Client\VM\Domain\Model\Storage\VirtioModel;
use GridCP\Proxmox_Client\VM\Domain\Model\UserModel;
use GridCP\Proxmox_Client\VM\Domain\Responses\VmResponse;
use GridCP\Proxmox_Client\VM\Domain\Responses\VmsResponse;

final class CreateVMinNode extends  GClientBase
{
    use GFunctions;


    public function __construct(Connection $connection, CookiesPVE $cookiesPVE)
    {
        parent::__construct($connection, $cookiesPVE);
    }

    public function __invoke(string  $node, int $vmid, ?int $cores, ?string $name, ?NetModel $net, ?bool $onBoot,
                             ?string $scsihw, ?ScsiModel $scsi, ?IdeModel $ide, ?SataModel $sata, ?VirtioModel $virtio, ?string $tags, ?string $boot,
                             ?int    $agent, ?IpModel $ip, ?UserModel $userModel, ?CpuModel $cpuModel, ?string $osType, ?string $bios, ?string $machinePC,
                             ?EfiModel $efi):?VmsResponse
    {




        try {
            $body = [
                'vmid' => $vmid,
                'cores' => $cores,
                'name' => $name,
                'net'.$net->GetIndex() =>$net->toString(),
                'onboot'=> $onBoot,
                'scsihw'=>$scsihw,
                'tags' => $tags,
                'boot'=>'order='.$boot,
                'ipconfig'.$ip->GetIndex() => $ip->toString(),
                'ciuser'=>$userModel->GetUserName(),
                'cipassword'=>$userModel->GetPassword(),
                'cpu' =>$cpuModel->getCpuTypes(),
                'memory'=>$cpuModel->getMemory(),
                'balloon'=>$cpuModel->getBallon(),
                'ostype'=>$osType,
                'bios'=>$bios,
               // 'efi'=>$efi->getStorage().$efi->getKey(),
               // 'machine'=>'type='.$machinePC
            ];
            (isset($scsi))?$body['scsi'.$scsi->GetIndex()]=$scsi->toString():null;
            (isset($ide))?$body['ide'.$ide->GetIndex()]=$ide->toString():null;
            (isset($sata))?$body['sata'.$sata->GetIndex()]=$sata->toString():null;
            (isset($virtio))?$body['virtio'.$virtio->GetIndex()]=$virtio->toString():null;
            $result = $this->Post("nodes/".$node."/qemu/", $body);
            $getContent = json_decode($result->getBody()->getContents());
            return new VmsResponse(...array_map($this->toResponse(), (array)$getContent));
        }catch (PostRequestException $e ){
            if ($e->getCode()===500) throw new VmErrorCreate($e->getMessage());
            return throw new VmErrorCreate("Error in create VM");
        }

    }

    public function toResponse():callable
    {
        return static fn($result):VmResponse=>new VmResponse(
            $result[0]
        );
    }
}