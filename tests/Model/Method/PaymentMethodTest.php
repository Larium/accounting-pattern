<?php

declare(strict_types = 1);

namespace Larium\Model\Method;

use Money\Money;
use PHPUnit\Framework\TestCase;

class PaymentMethodTest extends TestCase
{
    private $method;

    public function setUp(): void
    {
        $this->method = new PaymentMethod();
    }

    public function testShouldImplementInterface()
    {
        $this->assertInstanceOf(
            'Larium\Model\Method\PaymentMethodInterface',
            $this->method
        );
    }

    public function testShouldReturnResponseInstanceInPay()
    {
        $response = $this->method->pay(Money::EUR(100));

        $this->assertInstanceOf(
            'Larium\Model\Response',
            $response
        );
    }
}
