<?php
/**
 * EventManager
 * 
 * The WordPress event manager manages events using the WordPress plugin API.
 * 
 * @author Carl Alexander <contact@carlalexander.ca>
 *
 * @package    Facade
 * @subpackage Facade/Events
 */

namespace facade\events;


class EventManager {

    /**
     * add_callback
     * 
     * Adds a callback to a specific hook of the WordPress plugin API.
     *
     * @uses add_filter()
     *
     * @param string   $hook_name
     * @param callable $callback
     * @param int      $priority
     * @param int      $accepted_args
     */
    public function add_callback( string $hook_name , callable $callback , int $priority = 10 , int $accepted_args = 1 ) {
        
        \add_filter( $hook_name , $callback , $priority , $accepted_args );
    
    }


    /**
     * add_subscriber
     * 
     * Add an event subscriber.
     *
     * The event manager registers all the hooks that the given subscriber
     * wants to register with the WordPress Plugin API.
     *
     * @param SubscriberInterface $subscriber
     */
    public function add_subscriber( SubscriberInterface $subscriber ) : void {

        if ( $subscriber instanceof EventManagerAwareSubscriberInterface ) {

            $subscriber->set_event_manager( $this );

        }

        foreach ( $subscriber->get_subscribed_hooks() as $hook_name => $parameters ) {

            $this->add_subscriber_callback($subscriber, $hook_name, $parameters);

        }
        
    }


    /**
     * execute
     * 
     * Executes all the callbacks registered with the given hook.
     *
     * @uses do_action()
     *
     * @param string $hook_name
     */
    public function execute() : void {

        $args = \func_get_args();
        return \call_user_func_array( 'do_action' , $args );

    }


    /**
     * filter
     * 
     * Filters the given value by applying all the changes from the callbacks
     * registered with the given hook. Returns the filtered value.
     *
     * @uses apply_filters()
     *
     * @param string $hook_name
     * @param mixed  $value
     *
     * @return mixed
     */
    public function filter() {

        $args = \func_get_args();
        return \call_user_func_array( 'apply_filters' , $args );

    }


    /**
     * get_current_hook
     * 
     * Get the name of the hook that WordPress plugin API is executing. Returns
     * false if it isn't executing a hook.
     *
     * @uses current_filter()
     *
     * @return string|bool
     */
    public function get_current_hook() {

        return \current_filter();

    }


    /**
     * has_callback
     * 
     * Checks the WordPress plugin API to see if the given hook has
     * the given callback. The priority of the callback will be returned
     * or false. If no callback is given will return true or false if
     * there's any callbacks registered to the hook.
     *
     * @uses has_filter()
     *
     * @param string $hook_name
     * @param mixed  $callback
     *
     * @return bool|int
     */
    public function has_callback( string $hook_name , $callback = false ) {

        return has_filter( $hook_name , $callback );

    }


    /**
     * remove_callback
     * 
     * Removes the given callback from the given hook. The WordPress plugin API only
     * removes the hook if the callback and priority match a registered hook.
     *
     * @uses remove_filter()
     *
     * @param string   $hook_name
     * @param callable $callback
     * @param int      $priority
     *
     * @return bool
     */
    public function remove_callback( string $hook_name , callable $callback , int $priority = 10 ) : bool {
        
        return \remove_filter( $hook_name , $callback , $priority );

    }


    /**
     * remove_subscriber
     * 
     * Remove an event subscriber.
     *
     * The event manager removes all the hooks that the given subscriber
     * wants to register with the WordPress Plugin API.
     *
     * @param SubscriberInterface $subscriber
     */
    public function remove_subscriber( SubscriberInterface $subscriber ) : void {

        foreach ( $subscriber->get_subscribed_hooks() as $hook_name => $parameters ) {

            $this->remove_subscriber_callback( $subscriber , $hook_name , $parameters );

        }

    }


    /**
     * add_subscriber_callback
     * 
     * Adds the given subscriber's callback to a specific hook
     * of the WordPress plugin API.
     *
     * @param SubscriberInterface $subscriber
     * @param string              $hook_name
     * @param mixed               $parameters
     */
    private function add_subscriber_callback( SubscriberInterface $subscriber , string $hook_name , $parameters ) : void {
        
        if ( is_string( $parameters) ) {

            $this->add_callback( $hook_name , array( $subscriber , $parameters ));

        } elseif ( is_array( $parameters ) && isset( $parameters[0] ) ) {

            $this->add_callback( $hook_name , array( $subscriber , $parameters[0] ) , isset( $parameters[1] ) ? $parameters[1] : 10 , isset( $parameters[2] ) ? $parameters[2] : 1 );
       
        }

    }


    /**
     * remove_subscriber_callback
     * 
     * Removes the given subscriber's callback to a specific hook
     * of the WordPress plugin API.
     *
     * @param SubscriberInterface $subscriber
     * @param string              $hook_name
     * @param mixed               $parameters
     */
    private function remove_subscriber_callback( SubscriberInterface $subscriber , string $hook_name , $parameters ) : void {
        
        if ( is_string( $parameters ) ) {

            $this->remove_callback( $hook_name , array( $subscriber , $parameters ) );

        } else if ( is_array( $parameters ) && isset( $parameters[0] ) ) {

            $this->remove_callback( $hook_name , array( $subscriber , $parameters[0] ) , isset( $parameters[1] ) ? $parameters[1] : 10 );
        
        }

    }

}