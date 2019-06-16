<?php

declare(strict_types = 1);

namespace Larium\Model\Method;

use Larium\Model\ResponseInterface;
use Money\Money;

interface PaymentMethodInterface
{
    public function pay(Money $money): ResponseInterface;

    public function setActionParams(array $params): void;
}
