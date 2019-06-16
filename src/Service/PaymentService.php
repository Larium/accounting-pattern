<?php

declare(strict_types = 1);

namespace Larium\Service;

use AktiveMerchant\Billing\Gateway;
use Larium\Model\Method\PaymentMethodInterface;
use Larium\Model\Payment;
use Larium\Model\Method\CreditCardMethod;
use Larium\Model\Account\Account;
use Larium\Listener\PaymentListener;
use Larium\Event\EventHandler;
use AktiveMerchant\Billing\Base;
use AktiveMerchant\Billing\CreditCard;
use Larium\Model\ResponseInterface;
use Money\Money;

class PaymentService
{
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
     * @return ResponseInterface
     */
    public function pay(array $data): ResponseInterface
    {
        $payment = new Payment();
        $payment->setAmount(Money::EUR($data['amount']));

        $response = $payment->pay($this->getPaymentMethod($data));

        $this->getEventHandler($payment->popEvents())->handle();

        return $response;
    }

    private function getPaymentMethod(array $card): PaymentMethodInterface
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

    private function getGateway($name = 'bogus', array $options = array()): Gateway
    {
        return Base::gateway($name, $options);
    }

    private function getEventHandler(array $events): EventHandler
    {
        $merchant = new Account('Merchant');
        $buyer    = new Account('Buyer');

        return new EventHandler(new PaymentListener($merchant, $buyer), $events);
    }
}
