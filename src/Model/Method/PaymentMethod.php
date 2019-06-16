<?php

declare(strict_types = 1);

namespace Larium\Model\Method;

use Larium\Model\Response;
use Larium\Model\ResponseInterface;
use Money\Money;

class PaymentMethod implements PaymentMethodInterface
{
    public function pay(Money $amount): ResponseInterface
    {
        return new Response(true, 'PM123456');
    }

    public function setActionParams(array $params): void
    {
    }
}
