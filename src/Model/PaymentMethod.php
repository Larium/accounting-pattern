<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model;

use Money\Money;

class PaymentMethod implements PaymentMethodInterface
{
    public function pay(Money $amount)
    {
        return new Response(true, 'PM123456');
    }

    public function setActionParams(array $params)
    {
    }
}
