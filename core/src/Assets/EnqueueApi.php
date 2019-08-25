<?php
/**
 * EnqueueApi
 * 
 * Anqueue css and javascript assets..
 * 
 * @author Stephan Nijman <vanaf1979@gmail.com>
 *
 * @package    Facade
 * @subpackage Facade/Assets
 */

namespace Facade\Assets;

use \WP_Scripts;


class EnqueueApi {

    /**
     * styles.
     *
     * Enqueue stylesheets.
     *
     * @uses wp_enqueue_style https://developer.wordpress.org/reference/functions/wp_enqueue_style/
     * 
     * @param string $handle Name of the stylesheet. Should be unique.
     * @param string $src URL or path of the stylesheet. 
     * @param array $deps Array of dependancies
     * @param string $ver Version of this stylesheet
     * @param string $media Mediatypes this stylesheet is for.
     * 
     * @return void
     */
    public function style( string $handle , string $src , array $deps , string $ver , string $media ) : void {

        \wp_enqueue_style( $handle , $src , $deps , $ver , $media );
           
    }


    /**
     * styles.
     *
     * Enqueue stylesheets.
     *
     * @uses wp_enqueue_script https://developer.wordpress.org/reference/functions/wp_enqueue_script/
     * 
     * @param string $handle Name of the script.
     * @param string $src URL or path of the script. 
     * @param array $deps Array of dependancies.
     * @param string $ver Version of this script.
     * @param string $in_footer Enqueue script in footer or not.
     * 
     * @return void
     */
    public function script( string $handle , string $src , array $deps , string $ver , bool $in_footer ) : void {

        \wp_enqueue_script( $handle , $src , $deps , $ver , $media );
           
    }


    /**
     * is_style_handle_unique.
     *
     * Check if the asset handle is unique.
     *
     * @uses isset https://www.php.net/manual/en/function.isset.php
     * 
     * @param string $name asset handle.
     * 
     * @return bool
     */
    private function is_style_handle_unique( string $name ) : bool {
        
        global $wp_styles;

        if( ! isset( $wp_styles->registered[ $name ] ) ) {

            return true;

        } else {
            
            throw new \Exception( "Class Enqueue: Asset handle {$name} already exists." );
        }

    }


    /**
     * is_script_enqueued.
     *
     * Check if the asset handle is unique.
     *
     * @uses isset https://www.php.net/manual/en/function.isset.php
     * 
     * @param string $name asset handle.
     * 
     * @return bool
     */
    private function is_script_enqueued( string $name ) : bool {
        
        global $wp_scripts;

        if( ! isset( $wp_scripts->registered[ $name ] ) ) {

            return true;

        } else {
            
            throw new \Exception( "Class Enqueue: Asset handle {$name} already exists." );
        }

    }

}