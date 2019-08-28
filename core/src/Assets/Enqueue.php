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


class Enqueue {

    
    /**
     * the constructor.
     */
    public function __construct( ) { 


    }


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

}