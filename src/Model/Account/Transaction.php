<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model\Account;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Larium\Exception\UnableToPostException;
use Money\Money;

class Transaction
{
    protected $date;

    protected $entries;

    protected $wasPosted = false;

    public function __construct()
    {
        $this->date     = new DateTime();
        $this->entries  = new ArrayCollection();
    }

    public function add(Money $amount, Account $account)
    {
        $this->entries->add(
            new Entry($amount, $this->date, $account, $this)
        );
    }

    public function post()
    {
        if (false === $this->canPost()) {
            throw new UnableToPostException();
        }

        foreach ($this->entries as $entry) {
            $entry->post();
        }

        $this->wasPosted = true;
    }

    public function canPost()
    {
        return 0 === $this->balance()->getAmount();
    }

    public function getEntries()
    {
        return $this->entries;
    }

    private function balance()
    {
        if ($this->entries->isEmpty()) {
            return 0;
        }

        $balance = Money::EUR(0);
        foreach ($this->entries as $entry) {
            $balance = $balance->add($entry->getAmount());
        }

        return $balance;
    }
}
