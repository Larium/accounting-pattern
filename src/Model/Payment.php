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

    protected $transactionId;

    protected $referenceId;

    public function __construct()
    {
        $this->referenceId = $this->generateReferenceId();
    }

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
            $this->transactionId = $response->getTransactionId();
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

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function getReferenceId()
    {
        return $this->referenceId;
    }

    private function generateReferenceId()
    {
       return substr(uniqid('pm_', true), 0, -9);
    }
}
