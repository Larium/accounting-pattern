<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model\Account;

use Money\Money;
use Larium\Model\PaymentStub;

class AccountTest extends \PHPUnit_Framework_TestCase
{
    public function testAccountActions()
    {
        $buyer     = new Account('Buyer');
        $seller    = new Account('Seller');
        $provider  = new Account('Provider');
        $bank      = new Account('Bank');
        $payment   = new PaymentStub(Money::EUR(1000));

        $payment = null;

        $amount = Money::EUR(1000);
        $seller->deposit($amount, $buyer, $payment);

        $providerFee = new Fee(2.4, 20);
        $bankFee     = new Fee(0.4, 10);
        $prvAmount   = $providerFee->apply($amount);
        $bankAmount  = $bankFee->apply($amount);

        $provider->deposit($prvAmount, $seller, $payment);

        $bank->deposit($bankAmount, $provider, $payment);

        $this->showAccountEntries($seller);
        $this->showAccountEntries($provider);
        $this->showAccountEntries($bank);
    }

    private function writeln($string)
    {
        echo PHP_EOL;
        echo $string . PHP_EOL;
    }

    private function showAccountEntries($account)
    {
        $this->writeln($account->getDescription() . ' Transactions');
        foreach ($account->getEntries() as $entry) {
            $this->writeln($entry->getTypeString() . ' : ' . $entry->getAmountString());
        }
    }
}
