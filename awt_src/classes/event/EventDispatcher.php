<?php

namespace event;

use event\interfaces\IEvent;
use event\interfaces\IEventListener;

/**
 * Class EventDispatcher
 *
 * The EventDispatcher class manages the registration and dispatching
 * of events within the application. It allows event listeners to be
 * added for specific events and triggers those listeners when the
 * associated event is dispatched.
 */
class EventDispatcher
{
    /**
     * @var array $listeners
     * An associative array where keys are event names and values are
     * arrays of registered event listeners for those events.
     */
    public array $listeners = [];

    /**
     * Adds an event listener for a specified event name.
     *
     * This method allows a listener to be registered for a specific
     * event, enabling the listener to respond when that event is
     * dispatched.
     *
     * @param string $eventName The name of the event to listen for.
     * @param IEventListener $listener An instance of a class that implements
     * IEventListener to handle the event.
     * @return void
     */
    public function addListener(string $eventName, IEventListener $listener): void
    {
        $this->listeners[$eventName][] = $listener;
    }

    /**
     * Dispatches an event to its registered listeners.
     *
     * This method triggers the handling of an event by invoking all
     * listeners registered for that event name. The results of each
     * listener's handling are collected and returned.
     *
     * @param IEvent $event An instance of a class that implements
     * IEvent, representing the event to be dispatched.
     * @return array An array of results returned by each listener that
     * handled the event.
     */
    public function dispatch(IEvent $event): array
    {
        $eventName = $event->getName();

        $result = [];

        if (!empty($this->listeners[$eventName])) {
            foreach ($this->listeners[$eventName] as $listener) {
                $result[] = $listener->handle($event);
            }
        }


        return $result;
    }
}