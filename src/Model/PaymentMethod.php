<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model;

class PaymentMethod implements PaymentMethodInterface
{
    public function pay($amount)
    {
        return new Response(true);
    }
}
