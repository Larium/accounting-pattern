<?php

declare(strict_types = 1);

namespace Larium\Event;

use Symfony\Component\EventDispatcher\EventDispatcher;
use ReflectionClass;
use ReflectionMethod;

class EventHandler
{
    /**
     * @var EventDsipatcher
     */
    protected $dispatcher;

    /**
     * @var array
     */
    protected $events;

    public function __construct(object $listener, array $events)
    {
        $this->dispatcher = new EventDispatcher();
        $this->events = $events;
        $this->registerListener($listener);
    }

    public function handle(): void
    {
        foreach ($this->events as $event) {
            $this->dispatcher->dispatch($event->getName(), $event);
        }
    }

    private function registerListener(object $listener): void
    {
        $methods = (new ReflectionClass($listener))
            ->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            $name = $method->getName();
            $this->dispatcher->addListener($name, [$listener, $name]);
        }
    }
}
