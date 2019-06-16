<?php

declare(strict_types = 1);

namespace Larium\Model;

use Money\Money;

interface PaymentInterface
{
    /**
     * Get payment amount.
     *
     * @return Money
     */
    public function getAmount(): Money;

    /**
     * Returns a unique reference for this payment.
     *
     * @return string
     */
    public function getReferenceId();
}
