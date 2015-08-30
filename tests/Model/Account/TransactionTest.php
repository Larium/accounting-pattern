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
            echo $entry->getAccount()->getDescription()
                . ' Amount: ' . $entry->getAmount()->getAmount()
                . ' ['.$entry->getTypeString().']'
                . PHP_EOL;
        }

        $this->showAccountEntries($seller);
    }

    private function showAccountEntries($account)
    {
        $this->writeln($account->getDescription() . ' Transactions');
        foreach ($account->getEntries() as $entry) {
            $this->writeln('Payment: ' . $entry->getTransaction()->getPaymentEntry()->getAmountString());
            $this->writeln('Fee: ' . $entry->getTransaction()->getFeeEntry()->getAmountString());
            $this->writeln($entry->getTypeString() . ' : ' . $entry->getAmountString());
        }
    }

    private function writeln($string)
    {
        echo PHP_EOL;
        echo $string . PHP_EOL;
    }
}
