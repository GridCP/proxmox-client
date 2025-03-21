<?php
declare(strict_types=1);

namespace GridCP\Proxmox_Client\VM\App\Service\Help\So;


use GridCP\Proxmox_Client\Commons\Domain\Exceptions\NotFoundSOException;
use GridCP\Proxmox_Client\VM\App\Service\Help\So\Linux\CreateDataForLinuxVM;
use GridCP\Proxmox_Client\VM\App\Service\Help\So\Windows\CreateDataWindows11VM;
use GridCP\Proxmox_Client\VM\Domain\Contants\SoConst;

class SoVm
{
    const SO = [
        SoConst::LINUX => CreateDataForLinuxVM::class,
        SoConst::WINDOWS => CreateDataWindows11VM::class,

        SoConst::ROUTEROS6 => CreateDataForLinuxVM::class,
        SoConst::ROUTEROS7 => CreateDataForLinuxVM::class,
        SoConst::UBUNTU24 => CreateDataForLinuxVM::class,
        SoConst::UBUNTU22 => CreateDataForLinuxVM::class,
        SoConst::UBUNTU20 => CreateDataForLinuxVM::class,
        SoConst::UBUNTU18 => CreateDataForLinuxVM::class,
        SoConst::UBUNTU16 => CreateDataForLinuxVM::class,
        SoConst::DEBIAN10 => CreateDataForLinuxVM::class,
        SoConst::DEBIAN11 => CreateDataForLinuxVM::class,
        SoConst::DEBIAN12 => CreateDataForLinuxVM::class,
        SoConst::ALMA9 => CreateDataForLinuxVM::class,
        SoConst::ALMA8 => CreateDataForLinuxVM::class,
        SoConst::CENTOS7 => CreateDataForLinuxVM::class,
        SoConst::WINDOWS11 => CreateDataWindows11VM::class,
        SoConst::WINDOWS_11 => CreateDataWindows11VM::class
    ];
    
    public static function get(string $so): string|NotFoundSOException|null
    {
            try {
                
                return self::SO[$so];
    
            }catch (NotFoundSOException $e ){
    
                return new NotFoundSOException($so);
    
            }
    }
}