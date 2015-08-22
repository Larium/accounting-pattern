<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model\Account;

use Money\Money;

class Entry
{
    protected $amount;

    protected $date;

    protected $account;

    protected $transaction;

    protected $description;

    /**
     * @param mixed $amount
     * @param mixed $date
     * @param Account $account
     * @param Transaction $transaction
     * @param mixed $description
     * @return void
     */
    public function __construct(Money $amount, $date, Account $account, Transaction $transaction, $description = null)
    {
        $this->amount       = $amount;
        $this->createdAt    = $date;
        $this->account      = $account;
        $this->transaction  = $transaction;
    }

    public function post()
    {
        $this->account->getEntries()->add($this);
    }

    public function getAmount()
    {
        return $this->amount;
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

    public function getDescription()
    {
        return $this->description;
    }
}
