<?php
declare(strict_types=1);
namespace GridCP\Proxmox_Client\Commons\Domain\Models;


enum DiskTypePVE: string
{
    case SCSI='SCSI';
    case IDE='IDE';
    case VIRTIO='VIRTIO';
    case SATA='SATA';

}