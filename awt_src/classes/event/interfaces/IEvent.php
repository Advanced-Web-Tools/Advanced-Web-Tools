<?php

namespace event\interfaces;

/**
 * Interface IEvent
 *
 * The IEvent interface defines the structure for event objects within
 * the event-driven architecture. It specifies the methods that any
 * event class must implement to be used with the event system.
 */
interface IEvent
{
    /**
     * Retrieves the name of the event.
     *
     * This method returns the unique name of the event, which can be used
     * to identify the event when it is dispatched or listened to.
     *
     * @return string The name of the event.
     */
    public function getName(): string;

    /**
     * Returns an array of event data.
     *
     * This method returns an associative array containing any relevant
     * data associated with the event. This data can be used by listeners
     * to process the event accordingly.
     *
     * @return array An array of event-related data.
     */
    public function bundle(): array;
}
