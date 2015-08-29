<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Service;

use Larium\Model\Payment;
use Larium\Model\Method\CreditCardMethod;
use Larium\Listener\PaymentListener;
use Larium\Event\EventHandler;
use AktiveMerchant\Billing\Base;
use AktiveMerchant\Billing\CreditCard;
use Money\Money;

class PaymentService
{
    public function __construct()
    {

    }

    /**
     * Pay method for service.
     * Available data options
     * - amount The amount to pay (integer)
     * - creditcard_number
     * - creditcard_holder
     * - creditcard_cvv
     * - creditcard_month
     * - creditcard_year
     *
     * @param array $data
     * @return void
     */
    public function pay(array $data)
    {
        $payment = new Payment();
        $payment->setAmount(Money::EUR($data['amount']));

        $response = $payment->pay($this->getPaymentMethod($data));

        $this->getEventHandler($payment->popEvents())->handle();

    }

    private function getPaymentMethod(array $card)
    {
        $gateway = $this->getGateway();
        $cardData = [];
        foreach ($card as $key => $value) {
            if (0 === strpos($key, 'creditcard')) {
                $k = str_replace('creditcard_', null, $key);
                $cardData[$k] = $value;
            }
        }
        $creditCard = new CreditCard($cardData);

        return new CreditCardMethod($creditCard, $gateway);
    }

    private function getGateway($name = 'bogus', array $options = array())
    {
        return Base::gateway($name, $options);
    }

    private function getEventHandler(array $events)
    {
        return new EventHandler(new PaymentListener(), $events);
    }
}
