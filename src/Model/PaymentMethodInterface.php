<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model;

interface PaymentMethodInterface
{
    public function pay($amount);
}
