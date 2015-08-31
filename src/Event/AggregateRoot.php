<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Event;

trait AggregateRoot
{
    private $events = array();

    public function popEvents()
    {
        $events = $this->events;
        $this->events = array();

        return $events;
    }

    protected function raise($eventName, array $properties)
    {
        $this->events[] = new DomainEvent($eventName, $properties);
    }
}
