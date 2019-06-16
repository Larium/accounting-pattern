<?php

declare(strict_types = 1);

namespace Larium\Model\Method;

use AktiveMerchant\Billing\CreditCard;
use AktiveMerchant\Billing\Gateway;
use AktiveMerchant\Billing\Exception as AktiveMerchantException;
use Larium\Exception\GatewayException;
use Larium\Model\Method\PaymentMethodInterface;
use Larium\Model\Response;
use Larium\Model\ResponseInterface;
use Money\Money;

class CreditCardMethod implements PaymentMethodInterface
{
    /**
     * @var CreditCard
     */
    protected $creditCard;

    /**
     * @var Gateway
     */
    protected $gateway;

    /**
     * @var array
     */
    protected $actionParams = array();

    public function __construct(CreditCard $creditCard, Gateway $gateway)
    {
        $this->creditCard = $creditCard;
        $this->gateway    = $gateway;
    }

    public function pay(Money $money): ResponseInterface
    {
        try {
            $response = $this->gateway->purchase($this->amount($money), $this->creditCard, $this->actionParams);
        } catch (AktiveMerchantException $e) {
            throw new GatewayException($e->getMessage());
        }

        return new Response(
            $response->success(),
            $response->authorization(),
            $response->message()
        );
    }

    public function authorize(Money $money)
    {
        // code...
    }

    public function store()
    {
        // code...
    }

    public function setActionParams(array $params): void
    {
        $this->actionParams = $params;
    }

    private function amount(Money $money): string
    {
        $format = $this->gateway->money_format();

        if ($format == 'dollars') {
            return number_format($money->getAmount() / 100, 2, '', '');
        }

        return $money->getAmount();
    }
}
