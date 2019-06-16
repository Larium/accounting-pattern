<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model\Account;

use Money\Money;
use Larium\Model\PaymentStub;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
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

        $this->checkAccountEntries($seller, Money::EUR("1000"), Money::EUR("-44"));
        $this->checkAccountEntries($provider, Money::EUR("44"), Money::EUR("-14"));
        $this->checkAccountEntries($bank, Money::EUR("14"), Money::EUR(0));
    }

    private function checkAccountEntries(Account $account, Money $deposit, Money $withdraw)
    {
        foreach ($account->getEntries() as $entry) {
            if ($entry->getType() == Entry::DEPOSIT) {
                $this->assertEquals($deposit, $entry->getAmount());
            }
            if ($entry->getType() == Entry::WITHDRAW) {
                $this->assertEquals($withdraw, $entry->getAmount());
            }
        }
    }
}
