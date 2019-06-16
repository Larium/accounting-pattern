<?php

declare(strict_types = 1);

namespace Larium\Model\Account;

use Doctrine\Common\Collections\ArrayCollection;
use Money\Money;

class Account
{
    /**
     * @var ArrayCollection
     */
    private $entries;

    /**
     * @var string
     */
    private $description;

    public function __construct(string $description)
    {
        $this->entries = new ArrayCollection();

        $this->description = $description;
    }

    public function getEntries(): ArrayCollection
    {
        return $this->entries;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function withdraw(Money $amount, Account $target): void
    {
        $trx = new Transaction();
        $trx->add($amount->multiply(-1), $this, Entry::WITHDRAW);
        $trx->add($amount, $target, Entry::DEPOSIT);
        $trx->post();
    }

    public function deposit(Money $amount, Account $source): void
    {
        $trx = new Transaction();
        $trx->add($amount, $this, Entry::DEPOSIT);
        $trx->add($amount->multiply(-1), $source, Entry::WITHDRAW);
        $trx->post();
    }
}
