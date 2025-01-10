<?php

namespace event\interfaces;


/**
 * Interface IEventListener
 *
 * The IEventListener interface defines the contract for event listener classes
 * that handle specific events in the event-driven architecture. Any class
 * implementing this interface must provide an implementation for the
 * `handle` method to process incoming events.
 */
interface IEventListener
{
    /**
     * Handles the specified event.
     *
     * This method is called when an event is dispatched. It receives an
     * instance of IEvent, allowing the listener to process the event
     * and perform any necessary actions based on the event data.
     *
     * @param IEvent $event The event to handle.
     * @return array An array of results or responses from the event handling.
     */
    public function handle(IEvent $event): array;
}