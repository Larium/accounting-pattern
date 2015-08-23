<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model;

use Money\Money;

class PaymentStub extends Payment
{
    public function __construct(Money $amount)
    {
        parent::__construct();
        $this->amount = $amount;
        $this->state  = static::PAID;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getReferenceId()
    {
        return $this->referenceId;
    }
}
