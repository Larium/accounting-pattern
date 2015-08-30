<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Listener;

use Larium\Model\DomainEvent;

class PaymentListener
{
    public function paymentCaptured(DomainEvent $event)
    {
        echo sprintf('Payment %s was created', $event->payment->getReferenceId());
    }

    public function paymentCaptureFailed(DomainEvent $event)
    {
        echo sprintf('Payment %s failed!', $event->payment->getReferenceId());
    }
}
