<?php
/**
 * StyleAsset
 * 
 * Handles a single style asset.
 * 
 * @author Stephan Nijman <vanaf1979@gmail.com>
 *
 * @package    Facade
 * @subpackage Facade/Assets
 */

namespace Facade\Assets;

use Exception;
use WP_Scripts;

class StyleAsset {

    /**
     * data.
     *
     * @var array asset data.
     */
    private $data = null;


    /**
     * style_asset_defaults.
     *
     * @var array $style_defaults defaults array for a asset.
     */
    private $style_defaults = array(
        'handle' => null,
        'src' => null,
        'deps' => array(),
        'ver' => null,
        'media' => 'screen',
        'condition' => null
    );


    /**
     * __construct.
     *
     * Construct a new style asset.
     * 
     * @param array $data asset data.
     */
    public function __construct( array $data ) { 

        $this->data = $this->merge_defaults( $data );

        $this->validate_asset();
        
    }


    /**
     * enqueue.
     *
     * Enqueue the style asset.
     *
     * @uses wp_enqueue_style https://developer.wordpress.org/reference/functions/wp_enqueue_style/
     * 
     * @return void
     */
    public function enqueue() : void { 

        \wp_enqueue_style(
            $this->get_namespaced_asset_handle(),
            $this->get_asset_url(),
            $this->data['deps'],
            $this->get_asset_version(),
            $this->data['media']
        );

        if( $this->data['condition'] ) {

            $this->add_condition();

        }

    }


    /**
     * add_condition.
     *
     * Add browser condition to the asset.
     * 
     * @uses wp_style_add_data https://developer.wordpress.org/reference/functions/wp_style_add_data/
     * 
     * @return void
     */
    private function add_condition() : void {

        \wp_style_add_data(
            $this->get_namespaced_asset_handle(),
            'conditional',
            $this->data['condition']
        );

    }


    /**
     * merge_defaults.
     *
     * Complete the asset with style asset defaults.
     *
     * @uses array_merge https://www.php.net/manual/en/function.array-merge.php
     * 
     * @param array $asset asset data.
     * 
     * @return array
     */
    private function merge_defaults( array $asset ) : array {

        return \array_merge( $this->style_defaults , $asset ) ;

    }


    /**
     * validate_asset.
     *
     * Check asset data for erros.
     * 
     * @uses is_string https://www.php.net/manual/en/function.is-string.php
     * @uses is_array https://www.php.net/manual/en/function.is-array.php
     * 
     * @return void
     */
    private function validate_asset() : bool {

        if( ! $this->data['handle'] ) {

            throw new \Exception('Assets: No handle provided.');

        }

        if( ! is_string( $this->data['handle'] ) ) {

            throw new \Exception('Assets: Handle is not of type string.');

        }

        if( ! $this->is_style_handle_unique( $this->data['handle'] ) ) {

            throw new \Exception("Assets: Handle '{$this->data['handle']}' is not unique.");

        }

        if( ! $this->stylesheet_exists( $this->data['src'] ) ) {

            throw new \Exception("Assets: Local stylesheet '{$this->data['src']}' does not exist.");

        }

        if( isset( $this->data['deps'] ) and ! is_array( $this->data['deps'] ) ) {

            throw new \Exception("Assets: Dependencies is not of type array.");

        }

        if( isset( $this->data['ver'] ) and ! is_string( $this->data['ver'] ) ) {

            throw new \Exception("Assets: Version is not of type string.");

        }

        if( isset( $this->data['condition'] ) and ! is_string( $this->data['condition'] ) ) {

            throw new \Exception("Assets: Condition is not of type string.");

        }

        return true;

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

        return ! isset( $wp_styles->registered[ $this->get_namespaced_asset_handle() ] ) ? true : false;

    }


    /**
     * stylesheet_exists.
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
    private function stylesheet_exists( string $file ) : bool {

        return \filter_var( $file , FILTER_VALIDATE_URL ) || \file_exists( $this->get_asset_path() ) ? true : false;

    }

    /**
     * get_asset_handle.
     *
     * Get the handle for the asset.
     * 
     * @uses filter_var https://www.php.net/manual/en/function.filter-var.php
     * @uses wp_get_theme https://developer.wordpress.org/reference/functions/wp_get_theme/
     * 
     * @return string
     */
    private function get_asset_version() : string {

        if( \filter_var( $this->data['src'] , FILTER_VALIDATE_URL ) ) {
            
            return '';

        } else if( ! $this->data['ver'] ) {

            return \wp_get_theme()->get( 'Version' );

        } else {

            return $this->data['ver'];

        }

    }


    /**
     * get_asset_handle.
     *
     * Get the handle for the asset.
     * 
     * @uses wp_get_theme https://developer.wordpress.org/reference/functions/wp_get_theme/
     * 
     * @return string
     */
    private function get_namespaced_asset_handle() : string {

        return \wp_get_theme()->get( 'TextDomain' ) . '-' . $this->data['handle'];

    }


    /**
     * get_asset_url.
     *
     * return full url for asset.
     * 
     * @uses filter_var https://www.php.net/manual/en/function.filter-var.php
     * @uses get_template_directory_uri https://developer.wordpress.org/reference/functions/get_template_directory_uri/
     * 
     * @return string
     */
    private function get_asset_url() : string {

        if( \filter_var( $this->data['src'] , FILTER_VALIDATE_URL ) ) {

            return $this->data['src'];
        
        } else {

            return \get_template_directory_uri() . $this->data['src'];

        }
        
    }


    /**
     * get_asset_path.
     *
     * return full path to asset.
     * 
     * @uses get_template_directory https://developer.wordpress.org/reference/functions/get_template_directory/
     * 
     * @return string
     */
    private function get_asset_path() : string {

        return \get_template_directory() . DIRECTORY_SEPARATOR . $this->data['src'];
        
    }

}