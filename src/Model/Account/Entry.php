<?php

declare(strict_types = 1);

namespace Larium\Model\Account;

use Larium\Model\DescriptorInterface;
use Money\Money;
use DateTime;
use Larium\Model\Event\DomainEvent;

class Entry
{
    const DEPOSIT  = 1;
    const WITHDRAW = 2;
    const PAYMENT  = 3;
    const FEE      = 4;
    const REFUND   = 5;

    private static $types = array(
        1 => 'deposit',
        2 => 'withdraw',
        3 => 'payment',
        4 => 'fee',
        5 => 'refund'
    );

    /**
     * @var Money
     */
    private $amount;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var Account
     */
    private $account;

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var int
     */
    private $type;

    /**
     * @var DomainEvent
     */
    private $event;

    /**
     * @var DescriptorInterface
     */
    private $descriptor;

    public function __construct(
        Money $amount,
        DateTime $date,
        Account $account,
        Transaction $transaction,
        int $type,
        DomainEvent $event = null
    ) {
        $this->amount = $amount;
        $this->date = $date;
        $this->account = $account;
        $this->transaction = $transaction;
        $this->type = $type;
        $this->event = $event;
    }

    public function post(): void
    {
        $this->account->getEntries()->add($this);
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getAmountString(): string
    {
        return $this->amount->getCurrency()->getCode()
            . ' '
            . number_format($this->amount->getAmount() / 100, 2);
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }

    public function getDescriptor(): DescriptorInterface
    {
        return $this->descriptor;
    }

    public function getTypeString()
    {
        return array_key_exists($this->type, self::$types)
            ? self::$types[$this->type]
            : 'unknown';
    }

    public function getType()
    {
        return $this->type;
    }
}
