<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model;

interface PaymentInterface
{
    /**
     * Get payment amount.
     *
     * @return Money\Money
     */
    public function getAmount();

    /**
     * Returns a unique reference for this payment.
     *
     * @return string
     */
    public function getReferenceId();
}
