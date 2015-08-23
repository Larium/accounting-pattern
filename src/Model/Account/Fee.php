<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model\Account;

use Money\Money;

class Fee
{
    protected $percentage = 0;

    protected $flat = 0;

    public function __construct($percentage, $flat)
    {
        $this->percentage = $percentage;
        $this->flat       = $flat;
    }

    public function getPercentage()
    {
        return $this->percentage;
    }

    public function getFlat()
    {
        return $this->flat;
    }

    public function apply(Money $amount)
    {
        return $amount->multiply($this->percentage)
            ->divide(100)
            ->add(Money::EUR($this->flat));
    }
}
