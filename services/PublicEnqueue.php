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


final class PublicEnqueue implements Service, Registerable, Conditional {

    /**
     * the constructor.
     */
    public function __construct( ) { }


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

        \wp_enqueue_style( 
            $this->theme->textdomain  . '-app',
            $this->theme->path . '/style.css',
            array(),
            $this->theme->version,
            'all'
        );

    }


    /**
     * enqueue_scripts.
     *
     * Enqueue scripts for the frontend.
     *
     * @return void
     */
    public function enqueue_scripts() : void {

        \wp_enqueue_script(
            $this->theme->textdomain  . '-app',
            $this->theme->path . '/style.css',
            array(),
            $this->theme->version,
            'all'
        );

    }

}