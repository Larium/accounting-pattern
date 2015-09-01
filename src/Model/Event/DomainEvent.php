<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Model\Event;

use DateTime;
use RuntimeException;
use Symfony\Component\EventDispatcher\Event;

class DomainEvent extends Event
{
    protected $eventName;

    protected $date;

    public function __construct($eventName, array $params)
    {
        $this->eventName = $eventName;
        $this->params    = $params;
        $this->date      = new DateTime();
    }

    public function getName()
    {
        return $this->eventName;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function __get($name)
    {
        if (!isset($this->params[$name])) {
            throw new RuntimeException("Property '" . $name . "' does not exist on event '" . $this->eventName);
        }
        return $this->params[$name];
    }
}
