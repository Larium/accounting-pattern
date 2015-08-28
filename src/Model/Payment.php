<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Larium\Exception\RequiredAmountException;
use Larium\Model\Method\PaymentMethodInterface;
use Larium\Model\Method\CreditMethodInterface;
use Money\Money;

class Payment implements PaymentInterface
{
    const PENDING           = 'pending';
    const PAID              = 'paid';
    const FAILED            = 'failed';
    const REFUNDED          = 'refunded';
    const PARTIAL_REFUNDED  = 'partial_refunded';

    protected $state = self::PENDING;

    protected $amount = 0;

    protected $transactionId;

    protected $referenceId;

    public function __construct()
    {
        $this->referenceId  = $this->generateReferenceId();
        $this->amount       = Money::EUR(0);
    }

    public function getState()
    {
        return $this->state;
    }

    public function pay(PaymentMethodInterface $method)
    {
        if (null === $this->amount || $this->amount->getAmount() <= 0) {
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

    public function refund(CreditMethod $method, Money $money = null)
    {
        $refundMoney = $money ?: $this->amount;

        $response = $method->refund($this->transactionId, $refundMoney);

        if ($response->isSuccess()) {
            $this->state = static::REFUNDED;

            if ($refundMoney->lessThan($money)) {
                $this->state = state::PARTIAL_REFUNDED;
            }
        }

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

    public function getDescription()
    {
        return $this->getReferenceId();
    }

    private function generateReferenceId()
    {
        return substr(uniqid('pm_', true), 0, -9);
    }
}
