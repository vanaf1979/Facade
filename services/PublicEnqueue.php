<?php
/**
 * Enqueue styles and scripts for the frontend.
 *
 * @package    Theme
 * @subpackage Theme/Services
 */

namespace Theme\Services;


use Facade\Service;
use Facade\Registerable;
use Facade\Conditional;
use Facade\Assets\Enqueue;


final class PublicEnqueue implements Service, Registerable, Conditional {

    private $enqueue = null;

    /**
     * the constructor.
     */
    public function __construct( Enqueue $enqueue ) { 

        $this->enqueue = new $enqueue;

    }


    /**
     * is_needed.
     *
     * Should this service bee initialized?
     *
     * @return bool
     */
    static function is_needed() : bool {

        return ! \is_admin() ? true : false;

    }


    /**
     * register.
     *
     * Register hooks with WordPress.
     *
     * @return void
     */
    public function register() : void {

        \add_action( 'wp_enqueue_scripts' , array( $this , 'enqueue_styles' ) );
        \add_action( 'wp_enqueue_scripts' , array( $this , 'enqueue_scripts' ) );

    }


    /**
     * enqueue_styles.
     *
     * Enqueue stylesheets for the frontend.
     *
     * @return void
     */
    public function enqueue_styles() : void {

        $this->enqueue->styles( array(
            array(
                'handle' => 'app',
                'src' => 'style.css',
                'deps' => array(),
                'ver' => '1.0.0',
                'media' => 'all',
                'condition' => 'lt IE 9'
            )
        ));

    }


    /**
     * enqueue_scripts.
     *
     * Enqueue scripts for the frontend.
     *
     * @return void
     */
    public function enqueue_scripts() : void {

        $this->enqueue->scripts( array(
            array(
                'handle' => 'scripts',
                'src' => '/script.js',
                'deps' => array(),
                'ver' => '1.0.0',
                'in_footer' => true,
                'async' => true,
                'defer' => true,
                'condition' => 'lt IE 9'
            )
        ));

    }

}