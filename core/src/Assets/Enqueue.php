<?php
/**
 * Assats
 * 
 * Css and Javascript loader class.
 * 
 * @author Stephan Nijman <vanaf1979@gmail.com>
 *
 * @package    Facade
 * @subpackage Facade/Assets
 */

namespace Facade\Assets;

use \WP_Scripts;


class Enqueue {

    /**
     * deferables.
     *
     * @var string $deferables An array of assets to defer.
     */
    private $deferables = null;


    /**
     * csspath.
     *
     * @var string $csspath path to css files.
     */
    private $csspath = 'public/css';


    /**
     * jspath.
     *
     * @var string $jspath path to js files.
     */
    private $jspath = 'public/js';


    /**
     * the constructor.
     */
    public function __construct( WP_Scripts $scripts ) { 

        $this->scripts = $scripts;

    }


    /**
     * styles.
     *
     * Enqueue stylesheets.
     *
     * @uses wp_enqueue_style https://developer.wordpress.org/reference/functions/wp_enqueue_style/
     * 
     * @param array $asset asset data.
     * 
     * @return void
     */
    public function styles( array $assets ) : void {

        foreach( $assets as $asset ) {

            if( $this->is_style_handle_unique( $asset['handle'] ) and $this->does_asset_exist( $asset['src'] ) ) {

                \wp_enqueue_style(
                    $asset['handle'],
                    $asset['src'],
                    $asset['deps'],
                    $asset['ver'],
                    $asset['media']
                );
    
            }
            
        }
           
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
        
        global $wp_scripts;

        if( ! isset( $wp_scripts->registered[ $name ] ) ) {

            return true;

        } else {
            
            throw new \Exception( "Class Enqueue: Asset handle {$name} already exists." );
        }

    }


    /**
     * does_asset_exist.
     *
     * Check if asset is a local file adn exist.
     *
     * @uses filter_var https://www.php.net/manual/en/function.filter-var.php
     * @uses file_exists https://www.php.net/manual/en/function.file-exists.php
     * 
     * @param string $file file url.
     * 
     * @return bool
     */
    private function does_asset_exist( string $file ) : bool {

        if( \filter_var( $file , FILTER_VALIDATE_URL ) || \file_exists( $file ) ) {

            return true;

        } else {

            throw new \Exception( "Class Enqueue: Local file {$file} does not exist, and can not be enqueued." );
            
        }
    }

}