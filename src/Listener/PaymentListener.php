<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Listener;

use Larium\Model\Event\DomainEvent;
use Larium\Model\Account\Account;
use Larium\Model\Account\Entry;
use Larium\Model\Account\Fee;
use Larium\Model\Account\Transaction;

class PaymentListener
{
    public function __construct(Account $merchant, Account $buyer)
    {
        $this->merchant = $merchant;
        $this->buyer    = $buyer;
    }

    public function paymentCaptured(DomainEvent $event)
    {
        $provider   = new Account('provider');
        $payment    = $event->payment;
        $amount     = $payment->getAmount();

        $providerFee = new Fee(2.4, 20);
        $prvAmount   = $providerFee->apply($amount);

        $trx = new Transaction();
        $trx->add($amount->multiply(-1), $this->buyer, Entry::PAYMENT, $event);
        $trx->add($amount->subtract($prvAmount), $this->merchant, Entry::DEPOSIT, $event);
        $trx->add($prvAmount, $provider, Entry::FEE, $event);
        $trx->post();
        //echo sprintf('Payment %s was created', $event->payment->getReferenceId());
    }

    public function paymentCaptureFailed(DomainEvent $event)
    {
        echo sprintf('Payment %s failed!', $event->payment->getReferenceId());
    }
}
