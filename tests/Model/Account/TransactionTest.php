<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model\Account;

use Money\Money;

class TransactionTest extends \PHPUnit_Framework_TestCase
{
    public function testTwoLeggedAccountTransaction()
    {
        $seller = new Account('Seller');
        $buyer  = new Account('Buyer');

        $transaction = new Transaction();

        $transaction->add(Money::EUR(-1000), $buyer); # get 10 from buyer account
        $transaction->add(Money::EUR(1000), $seller); # set 10 to seller account
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
        $affiliate = new Account('Affiliate');

        $amount     = Money::EUR(1000);
        $feePercent = 2;
        $afflilPerc = 15;
        $fee        = $amount->multiply($feePercent)->divide(100); # 20
        $affilFee   = $fee->multiply($afflilPerc)->divide(100); # 3

        $transaction = new Transaction();

        $transaction->add($amount->multiply(-1), $buyer, 'Payment'); # get 10 from buyer account
        $transaction->add($fee->subtract($affilFee), $provider, 'Provider fee'); # provider will keep 0.17
        $transaction->add($affilFee, $affiliate, 'Affiliate fee from provider'); # affiliate will keep 0.03
        $transaction->add($amount->subtract($fee), $seller, 'Deposit to merchant'); # seller will get 9.8
        $transaction->post();

        echo PHP_EOL;
        foreach ($transaction->getEntries() as $entry) {
            echo $entry->getAccount()->getDescription()
                . ' Amount: ' . $entry->getAmount()->getAmount()
                . ' ['.$entry->getDescription().']'
                . PHP_EOL;
        }
    }
}
