<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Tests;

use GridCP\Proxmox_Client\Auth\Domain\Responses\LoginResponse;
use GridCP\Proxmox_Client\Commons\Domain\Entities\CookiesPVE;
use GridCP\Proxmox_Client\GClient;
use GridCP\Proxmox_Client\VM\App\Service\CreateVm;
use GridCP\Proxmox_Client\VM\Domain\Exceptions\VmErrorCreate;
use GridCP\Proxmox_Client\VM\Domain\Responses\VmsResponse;
use GridCP\Proxmox_Client\VM\Infrastructure\Symfony\Events\CreateVMDispatcher;
use GridCP\Proxmox_Client\VM\Infrastructure\Symfony\Events\CreateVMEvent;
use GridCP\Proxmox_Client\VM\Infrastructure\Symfony\Events\EventDispatcherInterfaceCustom;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use GridCP\Proxmox_Client\Commons\Domain\Entities\Connection;
class GClient_VM_Test extends TestCase
{

    private GClient $client;
    private LoginResponse $auth;
    private Connection $connection;
    private CookiesPVE $cookiesPVE;
    private CreateVm $createVM;
    private $mockEventDispatcher;

    public function setUp():void{
        $this->client = new GClient($_ENV['HOST'],$_ENV['USERNAME'],$_ENV['PASSWORD'],$_ENV['REALM']);
        $this->connection = new Connection($_ENV['HOST'],8006,$_ENV['USERNAME'],$_ENV['PASSWORD'],$_ENV['REALM']);
        $this->auth = $this->client->login();
        $this->cookiesPVE = new CookiesPVE($this->auth->getCSRFPreventionToken(),$this->auth->getCookies(),$this->auth->getTicket());
        $this->mockEventDispatcher = $this->createMock(CreateVMDispatcher::class);

    }

    //// TESTING VM
    public function testCreateVMOk():void
    {
       try {
           $this->mockEventDispatcher->expects($this->once()) // Se espera que se llame 1 vez
           ->method('execute');
            $createVM = new CreateVm($this->connection,$this->cookiesPVE,$this->mockEventDispatcher);
            $result = $createVM('ns1047', 102, 2, 'hostname', 0, 'virtio',
                'vmbr0', 1, true, 'virtio-scsi-pci', 'SCSI', 0, 'nvme', 'on', 'directsync', '/mnt/pve/nfs-iso/template/iso/debian-12.4.0-amd64-netinst.iso',
                'DEBIAN12', 0, 'nvme', 'scsi0', 1, 0, '5.134.113.50/24', '5.134.113.1', 'root', 'password', 'x86-64-v2-AES', 4096, 0,
                'l26', 'ovmf', 'pc-q35-8-1', 'nvme', 1);
           $this->assertInstanceOf(VmsResponse::class, $result);
        } catch (VmErrorCreate $e) {
            var_dump($e->getMessage());
        }



    }
    public function testCreateVMError():void
    {
        try {
            $this->mockEventDispatcher->expects($this->never()) // Se espera que se llame 1 vez
            ->method('execute')
            ->willReturn(true);
            $createVM = new CreateVm($this->connection, $this->cookiesPVE, $this->mockEventDispatcher);
            $result = $createVM('ns1047', 115, 2, 'hostname', 0, 'virtio',
                'vmbr0', 1, true, 'virtio-scsi-pci', 'SCSI', 0, 'nvme', 'on', 'directsync', '/mnt/pve/nfs-iso/gcp-images/Debian-12-x86_64-GridCP-PVE_KVM-20240610.qcow2',
                'deb12', 0, 'nvme', 'scsi0', 1, 0, '5.134.113.50/24', '5.134.113.1', 'root', 'password', 'x86-64-v2-AES', 4096, 0,
                'l26', 'ovmf', 'pc-q35-8-1', 'nvme', 1);
            $this->assertInstanceOf(VmsResponse::class, $result);
        } catch (VmErrorCreate $e) {
            $this->expectException(VmErrorCreate::class);
            var_dump($e->getMessage());
        }

    }
}