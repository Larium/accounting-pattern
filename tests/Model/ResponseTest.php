<?php

declare(strict_types = 1);

namespace Larium\Model;

use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testResponseCreation()
    {
        $response = new Response(true);

        $this->assertTrue($response->isSuccess());
        $this->assertNull($response->getTransactionId());
        $this->assertNull($response->getMessage());
    }

    public function testResponseCreationWithFullArgs()
    {
        $response = new Response(true, 'PM123456', 'Approved payment.');

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('PM123456', $response->getTransactionId());
        $this->assertEquals('Approved payment.', $response->getMessage());
    }
}
