<?php

declare(strict_types = 1);

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

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getReferenceId(): string
    {
        return $this->referenceId;
    }
}
