<?php

declare(strict_types = 1);

namespace Larium\Model\Account;

use Money\Money;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public function testTwoLeggedAccountTransaction()
    {
        $seller = new Account('Seller');
        $buyer  = new Account('Buyer');

        $transaction = new Transaction();

        $transaction->add(Money::EUR(-1000), $buyer, Entry::PAYMENT); # get 10 from buyer account
        $transaction->add(Money::EUR(1000), $seller, Entry::PAYMENT); # set 10 to seller account
        $transaction->post();

        # Both account should have entries.
        $this->assertFalse($buyer->getEntries()->isEmpty());
        $this->assertFalse($seller->getEntries()->isEmpty());

        # Both account should have 1 entry.
        $this->assertTrue($buyer->getEntries()->count() == 1);
        $this->assertTrue($seller->getEntries()->count() == 1);

        # Buyer entry should have negative amount
        $this->assertTrue($buyer->getEntries()->first()->getAmount()->lessThan(Money::EUR(0)));

        # Seller entry should have positive amount
        $this->assertTrue($seller->getEntries()->first()->getAmount()->greaterThan(Money::EUR(0)));
    }

    public function testMultiLeggedAccountTransaction()
    {
        $seller    = new Account('Seller');
        $buyer     = new Account('Buyer');
        $provider  = new Account('Provider');
        $bank      = new Account('Bank');

        $providerFee = new Fee(2.4, 20);
        $bankFee     = new Fee(0.4, 10);

        $amount = Money::EUR(1000);

        $prvAmount  = $providerFee->apply($amount);
        $bankAmount = $bankFee->apply($amount);

        $trx = new Transaction();
        $trx->add($amount->multiply(-1), $buyer, Entry::PAYMENT);
        $trx->add($amount->subtract($prvAmount), $seller, Entry::DEPOSIT);
        $trx->add($prvAmount, $provider, Entry::FEE);
        $trx->post();
        //$trx->add($prvAmount->subtract($bankAmount), $provider, null, Entry::FEE);
        //$trx->add($bankAmount, $bank, null, Entry::FEE);

        foreach ($trx->getEntries() as $entry) {
            if ($entry->getType() === Entry::PAYMENT) {
                $this->assertEquals(Money::EUR("-1000"), $entry->getAmount());
            }

            if ($entry->getType() === Entry::FEE) {
                $this->assertEquals(Money::EUR("44"), $entry->getAmount());
            }

            if ($entry->getType() === Entry::DEPOSIT) {
                $this->assertEquals(Money::EUR("956"), $entry->getAmount());
            }
        }
    }
}
