<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model;

use AktiveMerchant\Billing\CreditCard;
use AktiveMerchant\Billing\Gateway;
use AktiveMerchant\Billing\Exception as AktiveMerchantException;
use Larium\Exception\GatewayException;

class CreditCardMethod implements PaymentMethodInterface
{
    protected $creditCard;

    protected $gateway;

    protected $actionParams = array();

    public function __construct(CreditCard $creditCard, Gateway $gateway)
    {
        $this->creditCard = $creditCard;
        $this->gateway    = $gateway;
    }

    public function pay($amount)
    {
        try {
            $response = $this->gateway->purchase($amount, $this->creditCard, $this->actionParams);
        } catch (AktiveMerchantException $e) {
            throw new GatewayException($e->getMessage());
        }

        return new Response(
            $response->success(),
            $response->authorization(),
            $response->message()
        );
    }

    public function setActionParams(array $params)
    {
        $this->actionParams = $params;
    }
}
