<?php

/**
 * An event subscriber who stores an instance of the WordPress event
 * manager so that it can trigger additional events.
 *
 * @author Carl Alexander <contact@carlalexander.ca>
 */
abstract class AbstractEventManagerAwareSubscriber implements EventManagerAwareSubscriberInterface
{
    /**
     * The WordPress event manager.
     *
     * @var EventManager
     */
    protected $event_manager;

    /**
     * Set the WordPress event manager for the subscriber.
     *
     * @param EventManager $event_manager
     */
    public function set_event_manager(EventManager $event_manager)
    {
        $this->event_manager = $event_manager;
    }
}