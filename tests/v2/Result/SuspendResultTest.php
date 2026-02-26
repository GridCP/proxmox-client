<?php

namespace GridCP\Proxmox\Api\Tests\Result;

use GridCP\Proxmox\Api\Result\SuspendResult;
use PHPUnit\Framework\TestCase;

class SuspendResultTest extends TestCase
{
    public function testFromArrayWithData(): void
    {
        $array = ['data' => 'UPID:ns1047:0038862F:3D66BAFA:68D2D4BA:qmpause:101:root@pam:'];
        $result = SuspendResult::fromArray($array);

        $this->assertSame('UPID:ns1047:0038862F:3D66BAFA:68D2D4BA:qmpause:101:root@pam:', $result->upid);
    }
}
