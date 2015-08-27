<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model\Method;

use Money\Money;

interface PaymentMethodInterface
{
    public function pay(Money $money);

    public function setActionParams(array $params);
}
