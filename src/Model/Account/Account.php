<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model\Account;

use Doctrine\Common\Collections\ArrayCollection;
use Money\Money;

class Account
{
    protected $balance;

    protected $entries;

    protected $description;

    public function __construct($description)
    {
        $this->entries = new ArrayCollection();

        $this->description = $description;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function getEntries()
    {
        return $this->entries;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function withdraw(Money $amount, Account $target, $descriptor)
    {
        $trx = new Transaction();
        $trx->add($amount->multiply(-1), $this, $descriptor, Entry::WITHDRAW);
        $trx->add($amount, $target, $descriptor, Entry::DEPOSIT);
        $trx->post();
    }

    public function deposit(Money $amount, Account $source, $descriptor)
    {
        $trx = new Transaction();
        $trx->add($amount, $this, $descriptor, Entry::DEPOSIT);
        $trx->add($amount->multiply(-1), $source, $descriptor, Entry::WITHDRAW);
        $trx->post();
    }
}
