<?php

declare(strict_types = 1);

namespace Larium\Model\Method;

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
