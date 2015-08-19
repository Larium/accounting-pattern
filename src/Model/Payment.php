<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model;

use Larium\Exception\InvalidAmountException;
use Larium\Exception\RequiredAmountException;

class Payment
{
    const PENDING   = 'pending';
    const PAID      = 'paid';
    const FAILED    = 'failed';

    protected $state = self::PENDING;

    protected $amount;

    public function getState()
    {
        return $this->state;
    }

    public function pay(PaymentMethodInterface $method)
    {
        if (null === $this->amount) {
            throw new RequiredAmountException('Payment amount is required.');
        }

        if (!is_int($this->amount)) {
            throw new InvalidAmountException('Payment amount must be an integer.');
        }

        $response = $method->pay($this->amount);

        if ($response->isSuccess()) {
            return $this->state = static::PAID;
        }

        $this->state = static::FAILED;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }
}
