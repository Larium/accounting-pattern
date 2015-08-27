<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Model;

use AktiveMerchant\Billing\Gateway;
use AktiveMerchant\Billing\Exception as AktiveMerchantException;
use Larium\Exception\GatewayException;
use Money\Money;

class CreditMethod
{
    protected $gateway;

    public function __construct(Gateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function refund($transactionId, Money $money = null)
    {
        // code...
    }

    public function void($transactionId)
    {
        // code...
    }
}
