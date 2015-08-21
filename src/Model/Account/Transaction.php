<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model\Account;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Larium\Exception\UnableToPostException;

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

    public function add($amount, Account $account)
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
        return 0 === $this->balance();
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

        $balance = 0;
        foreach ($this->entries as $entry) {
            $balance += $entry->getAmount();
        }

        return $balance;
    }
}
