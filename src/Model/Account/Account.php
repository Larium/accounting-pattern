<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model\Account;

use Doctrine\Common\Collections\ArrayCollection;

class Account
{
    protected $balance;

    protected $entries;

    public function __construct()
    {
        $this->entries = new ArrayCollection();
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function getEntries()
    {
        return $this->entries;
    }
}
