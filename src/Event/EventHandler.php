<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

namespace Larium\Event;

use Symfony\Component\EventDispatcher\EventDispatcher;
use ReflectionClass;
use ReflectionMethod;

class EventHandler
{
    protected $dispatcher;

    protected $events;

    public function __construct($listener, array $events)
    {
        $this->dispatcher = new EventDispatcher();
        $this->events     = $events;
        $this->registerListener($listener);
    }

    public function handle()
    {
        foreach ($this->events as $event) {
            $this->dispatcher->dispatch($event->getName(), $event);
        }
    }

    private function registerListener($listener)
    {
        $methods = (new ReflectionClass($listener))
            ->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            $name = $method->getName();
            $this->dispatcher->addListener($name, [$listener, $name]);
        }
    }
}
