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

use Facade\Assets\StyleAsset;
use Facade\Assets\ScriptAsset;

class Enqueue {


    /**
     * deferables.
     *
     * @var array list of script to defer.
     */
    public static $deferables = array();


    /**
     * asyncables.
     *
     * @var array list of script to async.
     */
    public static $asyncables = array();


    /**
     * hooked.
     *
     * @var bool is this class hooked.
     */
    public static $hooked = false;

    
    /**
     * the constructor.
     */
    public function __construct( ) { }


    /**
     * styles.
     *
     * Enqueue stylesheets.
     * 
     * @param array $asset asset data.
     * 
     * @return void
     */
    public function styles( array $assets ) : void {

        foreach( $assets as $asset ) {

            $styleasset = new StyleAsset( $asset );
            $styleasset->enqueue();

        }
           
    }


    /**
     * scripts.
     *
     * Enqueue javascripts.
     * 
     * @param array $asset asset data.
     * 
     * @return void
     */
    public function scripts( array $assets ) : void {

        foreach( $assets as $asset ) {

            $scriptasset = new ScriptAsset( $asset );
            $scriptasset->enqueue();

            $this->check_async_defer( $asset );

        }

    }


    /**
     * check_async_defer.
     *
     * Check is asset needs to be asynced or defered.
     * 
     * @uses add_filter https://developer.wordpress.org/reference/functions/add_filter/
     * 
     * @param array $asset asset data.
     * 
     * @return void
     */
    private function check_async_defer( array $asset ) : void {

        if( ( $asset['async'] || $asset['defer'] ) && ! $this->hooked ) {
            
            $this->hooked = \add_filter( 'script_loader_tag' , array( $this , 'async_defer_scripts' ) , 10 , 2 );

        }

        if( $asset['async'] ) {

            $this->asyncables[] = $asset['handle'];

        }

        if( $asset['defer'] ) {

            $this->deferables[] = $asset['handle'];

        }
        
    }


    /**
     * async_defer_scripts.
     *
     * Add async/defer to script tag if needed.
     * 
     * @param string $tag the original script tag.
     * @param string $handle the script name.
     * 
     * @return void
     */
    public function async_defer_scripts( string $tag , string $handle ) : string {

        $addition = '';

        $addition .= \in_array( $handle , $this->asyncables ) ? 'async ' : '';

        $addition .= \in_array( $handle , $this->deferables ) ? 'defer ' : '';

        return $addition > '' ? \str_replace( "src='" , $addition . "src='" , $tag ) : $tag;

    }

}