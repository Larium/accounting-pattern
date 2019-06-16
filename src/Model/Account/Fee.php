<?php

declare(strict_types = 1);

namespace Larium\Model\Account;

use Money\Money;

class Fee
{
    /**
     * @var float
     */
    private $percentage = 0;

    /**
     * @var int
     */
    private $flat = 0;

    public function __construct(float $percentage, int $flat)
    {
        $this->percentage = $percentage;
        $this->flat = $flat;
    }

    public function getPercentage(): float
    {
        return $this->percentage;
    }

    public function getFlat(): int
    {
        return $this->flat;
    }

    public function apply(Money $amount): Money
    {
        $currency = $amount->getCurrency();

        return $amount->multiply($this->percentage)
            ->divide(100)
            ->add(new Money($this->flat, $currency));
    }
}
