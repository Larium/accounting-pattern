<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model;

class PaymentMethodTest extends \PHPUnit_Framework_TestCase
{
    private $method;

    public function setUp()
    {
        $this->method = new PaymentMethod();
    }

    public function testShouldImplementInterface()
    {
        $this->assertInstanceOf(
            'Larium\Model\PaymentMethodInterface',
            $this->method
        );
    }

    public function testShouldReturnResponseInstanceInPay()
    {
        $response = $this->method->pay(100);

        $this->assertInstanceOf(
            'Larium\Model\Response',
            $response
        );
    }
}
