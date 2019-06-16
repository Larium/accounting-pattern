<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Service;

use PHPUnit\Framework\TestCase;

class PaymentServiceTest extends TestCase
{
    public function testPayService()
    {
        $service = new PaymentService();

        $data = [
            'amount'  => 1000,
            'creditcard_number' => '1',
            'creditcard_first_name' => 'JOHN',
            'creditcard_last_name' => 'DOE',
            'creditcard_verification_value' => '123',
            'creditcard_month' => '01',
            'creditcard_year' => '2020',
        ];

        $response = $service->pay($data);

        $this->assertTrue($response->isSuccess());
    }
}
