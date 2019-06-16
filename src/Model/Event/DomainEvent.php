<?php

declare(strict_types = 1);

namespace Larium\Model\Event;

use DateTime;
use RuntimeException;
use Symfony\Component\EventDispatcher\Event;

class DomainEvent extends Event
{
    /**
     * @var string
     */
    protected $eventName;

    /**
     * @var DateTime
     */
    protected $date;

    public function __construct(string $eventName, array $params)
    {
        $this->eventName = $eventName;
        $this->params = $params;
        $this->date = new DateTime();
    }

    public function getName(): string
    {
        return $this->eventName;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function __get(string $name)
    {
        if (!isset($this->params[$name])) {
            throw new RuntimeException("Property '" . $name . "' does not exist on event '" . $this->eventName);
        }

        return $this->params[$name];
    }
}
