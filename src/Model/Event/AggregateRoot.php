<?php

declare(strict_types = 1);

namespace Larium\Model\Event;

trait AggregateRoot
{
    private $events = array();

    public function popEvents(): array
    {
        $events = $this->events;
        $this->events = array();

        return $events;
    }

    protected function raise($eventName, array $properties): void
    {
        $this->events[] = new DomainEvent($eventName, $properties);
    }
}
