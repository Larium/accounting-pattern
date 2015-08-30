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

    public function withdraw(Money $amount, Account $target)
    {
        $trx = new Transaction();
        $trx->add($amount->multiply(-1), $this, Entry::WITHDRAW);
        $trx->add($amount, $target, Entry::DEPOSIT);
        $trx->post();
    }

    public function deposit(Money $amount, Account $source)
    {
        $trx = new Transaction();
        $trx->add($amount, $this, Entry::DEPOSIT);
        $trx->add($amount->multiply(-1), $source, Entry::WITHDRAW);
        $trx->post();
    }
}
