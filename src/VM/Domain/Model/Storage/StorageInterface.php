<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\VM\Domain\Model\Storage;

interface StorageInterface
{
    function GetIndex(): ?int;
    function GetDiskStorage(): ?string;
    function GetDiscard(): ?string;
    function GetCache(): ?string;
    function GetImportFrom():?string;
    function toString():?string;
}