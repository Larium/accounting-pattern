<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model\Account;

class TransactionTest extends \PHPUnit_Framework_TestCase
{
    public function testTwoLeggedAccountTransaction()
    {
        $seller = new Account();
        $buyer  = new Account();

        $transaction = new Transaction();

        $transaction->add(-1000, $buyer); # get 10 from buyer account
        $transaction->add(1000, $seller); # set 10 to seller account
        $transaction->post();

        # Both account should have entries.
        $this->assertFalse($buyer->getEntries()->isEmpty());
        $this->assertFalse($seller->getEntries()->isEmpty());

        # Both account should have 1 entry.
        $this->assertTrue($buyer->getEntries()->count() == 1);
        $this->assertTrue($seller->getEntries()->count() == 1);

        # Buyer entry should have negative amount
        $this->assertTrue($buyer->getEntries()->first()->getAmount() < 0);

        # Seller entry should have positive amount
        $this->assertTrue($seller->getEntries()->first()->getAmount() > 0);
    }

    public function testMultiLeggedAccountTransaction()
    {
        $seller = new Account();
        $buyer  = new Account();
        $provider = new Account();

        $amount     = 1000;
        $feePercent = 2;
        $fee        = $amount * 2 / 100; # 20

        $transaction = new Transaction();

        $transaction->add(-$amount, $buyer); # get 10 from buyer account
        $transaction->add($fee, $provider); # provider will keep 0.2
        $transaction->add($amount - $fee, $seller); # seller will get 9.8
        $transaction->post();
    }
}
