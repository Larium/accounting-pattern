<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model\Account;

class Entry
{
    protected $amount;

    protected $date;

    protected $account;

    protected $transaction;

    protected $description;

    public function __construct($amount, $date, Account $account, Transaction $transaction)
    {
        $this->amount       = $amount;
        $this->createdAt    = $date;
        $this->account      = $account;
        $this->transaction  = $transaction;

    }

    public function post()
    {
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
