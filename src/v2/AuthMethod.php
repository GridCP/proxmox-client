<?php

declare(strict_types=1);

namespace GridCP\Proxmox\Api;

enum AuthMethod: string
{
    case API_TOKEN = 'api-token';
    case TICKET = 'ticket';
}
