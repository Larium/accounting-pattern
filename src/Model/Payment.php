<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Larium\Exception\InvalidAmountException;
use Larium\Exception\RequiredAmountException;
use Money\Money;
use Larium\Model\Account\Entry;

class Payment implements PaymentInterface
{
    const PENDING   = 'pending';
    const PAID      = 'paid';
    const FAILED    = 'failed';

    protected $state = self::PENDING;

    protected $amount;

    protected $transactionId;

    protected $referenceId;

    protected $entries;

    public function __construct()
    {
        $this->referenceId = $this->generateReferenceId();
        $this->entries     = new ArrayCollection();
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

        $response = $method->pay($this->amount);

        if ($response->isSuccess()) {
            $this->transactionId = $response->getTransactionId();
            $this->state         = static::PAID;

            return $response;
        }

        $this->state = static::FAILED;

        return $response;
    }

    public function setAmount(Money $amount)
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

    public function getDescription()
    {
        return $this->getReferenceId();
    }

    public function getEntries()
    {
        return $this->entries;
    }

    public function addEntry(Entry $entry)
    {
        $this->entries->add($entry);
    }
}
