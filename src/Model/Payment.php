<?php

declare(strict_types = 1);

namespace Larium\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Larium\Exception\RequiredAmountException;
use Larium\Model\Method\PaymentMethodInterface;
use Larium\Model\Method\CreditMethodInterface;
use Larium\Model\Event\AggregateRoot;
use Money\Money;

class Payment implements PaymentInterface
{
    use AggregateRoot;

    const PENDING           = 'pending';
    const PAID              = 'paid';
    const FAILED            = 'failed';
    const REFUNDED          = 'refunded';
    const PARTIAL_REFUNDED  = 'partial_refunded';

    /**
     * @var string
     */
    protected $state = self::PENDING;

    /**
     * @var Money
     */
    protected $amount;

    /**
     * @var string
     */
    protected $transactionId;

    /**
     * @var string
     */
    protected $referenceId;

    public function __construct()
    {
        $this->referenceId = $this->generateReferenceId();
        $this->amount = Money::EUR(0);
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function pay(PaymentMethodInterface $method): ResponseInterface
    {
        if (null === $this->amount || $this->amount->getAmount() <= 0) {
            throw new RequiredAmountException('Payment amount is required.');
        }

        $response = $method->pay($this->amount);

        if ($response->isSuccess()) {
            $this->transactionId = $response->getTransactionId();
            $this->state = static::PAID;
            $this->raise('paymentCaptured', ['payment' => $this]);

            return $response;
        }

        $this->state = static::FAILED;
        $this->raise('paymentCaptureFailed', ['payment' => $this]);

        return $response;
    }

    public function refund(CreditMethod $method, Money $money = null): ResponseInterface
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

    public function setAmount(Money $amount): void
    {
        $this->amount = $amount;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getReferenceId(): string
    {
        return $this->referenceId;
    }

    public function getDescription(): string
    {
        return $this->getReferenceId();
    }

    private function generateReferenceId(): string
    {
        return substr(uniqid('pm_', true), 0, -9);
    }
}
