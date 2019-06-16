<?php

declare(strict_types = 1);

namespace Larium\Model\Account;

use DateTime;
use Money\Money;
use Doctrine\Common\Collections\ArrayCollection;
use Larium\Exception\UnableToPostException;
use Larium\Money\DescriptorInterface;
use Larium\Model\Event\DomainEvent;

class Transaction
{
    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var ArrayCollection
     */
    protected $entries;

    /**
     * @var bool
     */
    protected $wasPosted = false;

    public function __construct()
    {
        $this->date = new DateTime();
        $this->entries  = new ArrayCollection();
    }

    public function add(Money $amount, Account $account, int $type, DomainEvent $event = null)
    {
        $this->entries->add(
            new Entry($amount, $this->date, $account, $this, $type, $event)
        );
    }

    public function post(): void
    {
        if (false === $this->canPost()) {
            throw new UnableToPostException(
                sprintf('The balance is not zero (%s)', $this->balance()->getAmount())
            );
        }

        foreach ($this->entries as $entry) {
            $entry->post();
        }

        $this->wasPosted = true;
    }

    public function canPost(): bool
    {
        return Money::EUR(0)->equals($this->balance());
    }

    public function getEntries(): ArrayCollection
    {
        return $this->entries;
    }

    public function getLinkedEntry(Entry $entry): Entry
    {
        return $this->entries->filter(function ($e) use ($entry) {
            return $e !== $entry;
        })->first();
    }

    public function getFeeEntry(): Entry
    {
        return $this->entries->filter(function ($e) {
            return $e->getType() === Entry::FEE;
        })->first();
    }

    public function getPaymentEntry(): Entry
    {
        return $this->entries->filter(function ($e) {
            return $e->getType() === Entry::PAYMENT;
        })->first();
    }

    private function balance(): Money
    {
        $balance = Money::EUR(0);

        if ($this->entries->isEmpty()) {
            return $balance;
        }

        foreach ($this->entries as $entry) {
            $balance = $balance->add($entry->getAmount());
        }

        return $balance;
    }
}
