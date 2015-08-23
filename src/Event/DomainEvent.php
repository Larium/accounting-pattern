<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Event;

use DateTime;

abstract class DomainEvent
{
    protected $occuredOn;

    public function __construct()
    {
        $this->occuredOn = new DateTime();
    }
}
