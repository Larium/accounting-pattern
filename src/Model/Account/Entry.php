<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model\Account;

use Money\Money;
use DateTime;
use Larium\Model\DescriptorInterface;

class Entry
{
    const DEPOSIT  = 1;
    const WITHDRAW = 2;

    private static $types = array(
        1 => 'deposit',
        2 => 'withdraw'
    );

    protected $amount;

    protected $date;

    protected $account;

    protected $transaction;

    protected $descriptor;

    protected $type;

    public function __construct(
        Money $amount,
        DateTime $date,
        Account $account,
        Transaction $transaction,
        DescriptorInterface $descriptor,
        $type
    ) {
        $this->amount       = $amount;
        $this->createdAt    = $date;
        $this->account      = $account;
        $this->transaction  = $transaction;
        $this->descriptor   = $descriptor;
        $this->type         = $type;

        $this->descriptor->addEntry($this);
    }

    public function post()
    {
        $this->account->getEntries()->add($this);
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getAmountString()
    {
        return $this->amount->getCurrency()->getName()
            . ' '
            . number_format($this->amount->getAmount() / 100, 2);
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function getTransaction()
    {
        return $this->transaction;
    }

    public function getDescriptor()
    {
        return $this->descriptor;
    }

    public function getTypeString()
    {
        return array_key_exists($this->type, static::$types)
            ? static::$types[$this->type]
            : 'unknown';
    }
}
