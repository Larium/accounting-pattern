<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model\Account;

use Doctrine\Common\Collections\ArrayCollection;

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
}
