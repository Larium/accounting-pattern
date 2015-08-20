<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model\Account;

use DateTime;

class Transaction
{
    protected $date;

    protected $entries;

    public function __construct()
    {
        $this->date = new DateTime();
    }

    public function add($amount, Account $account)
    {
        $this->entries->add(
            new Entry($amount, $this->date, $account, $this)
        );
    }
}
