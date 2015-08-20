<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model;

class ResponseTest extends \PHPUnit_Framework_TestCase
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
