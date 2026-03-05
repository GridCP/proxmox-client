<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api\Api\Parameters;

enum MigrationType: string
{
    case SECURE = 'secure';
    case INSECURE = 'insecure';
}
