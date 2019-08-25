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
use Facade\Assets\EnqueueApi;


class Enqueue {

    /**
     * enqueue_api.
     *
     * @var EnqueueApi $enqueue_api enqueue api instance.
     */
    private $enqueue_api = null;

    /**
     * style_asset_defaults.
     *
     * @var array $style_asset_defaults default array for a asset.
     */
    private $style_asset_defaults = array(
        'handle' => null,
        'src' => null,
        'deps' => array(),
        'ver' => '1.0.0',
        'media' => 'screen',
        'defer' => false,
        'async' => false,
        'condition' => null
    );


    /**
     * the constructor.
     */
    public function __construct( ) { 

        $this->enqueue_api = new EnquuueApi();

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

            $asset = $this->complete_style_asset( $asset );

            if( $this->enqueue_api->is_style_handle_unique( $asset['handle'] ) and $this->does_asset_exist( $asset['src'] ) ) {

                $this->enqueue_api->style(
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
     * complete_style_asset.
     *
     * Complete the asset with style asset defaults.
     *
     * @uses array_merge https://www.php.net/manual/en/function.array-merge.php
     * 
     * @param array $asset asset data.
     * 
     * @return array
     */
    private function complete_style_asset( array $asset ) : array {

        return \array_merge( $this->style_asset_defaults , $asset ) ;

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