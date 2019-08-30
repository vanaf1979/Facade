<?php
/**
 * ScriptAsset
 * 
 * Handles a single script asset.
 * 
 * @author Stephan Nijman <vanaf1979@gmail.com>
 *
 * @package    Facade
 * @subpackage Facade/Assets
 */

namespace Facade\Assets;

use Exception;
use WP_Scripts;

class ScriptAsset {

    /**
     * data.
     *
     * @var array asset data.
     */
    private $data = null;


    /**
     * script_defaults.
     *
     * @var array $script_defaults defaults array for the asset.
     */
    private $script_defaults = array(
        'handle' => null,
        'src' => null,
        'deps' => array(),
        'ver' => null,
        'in_footer' => true,
        'defer' => false,
        'async' => false,
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

        $this->validate_asset( );
        
    }


    /**
     * enqueue.
     *
     * Enqueue the script asset.
     *
     * @uses wp_enqueue_script https://developer.wordpress.org/reference/functions/wp_enqueue_script/
     * 
     * @return void
     */
    public function enqueue( ) : void { 

        \wp_enqueue_script(
            $this->data['handle'],
            \get_template_directory_uri() . $this->data['src'],
            $this->data['deps'],
            $this->data['ver'],
            $this->data['in_footer']
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
     * @uses wp_script_add_data https://developer.wordpress.org/reference/functions/wp_script_add_data/
     * 
     * @return void
     */
    private function add_condition() : void {

        \wp_script_add_data(
            $this->data['handle'],
            'conditional',
            $this->data['condition']
        );

    }


    /**
     * merge_defaults.
     *
     * Complete the asset with script asset defaults.
     *
     * @uses array_merge https://www.php.net/manual/en/function.array-merge.php
     * 
     * @param array $asset asset data.
     * 
     * @return array
     */
    private function merge_defaults( array $asset ) : array {

        return \array_merge( $this->script_defaults , $asset ) ;

    }


    /**
     * validate_asset.
     *
     * Check asset data for erros.
     * 
     * @uses is_string https://www.php.net/manual/en/function.is-string.php
     * 
     * @return void
     */
    private function validate_asset( ) : bool {

        if( ! $this->data['handle'] ) {

            throw new \Exception('Assets: No handle provided.');

        }

        if( ! is_string( $this->data['handle'] ) ) {

            throw new \Exception('Assets: Handle is not of type string.');

        }

        if( ! $this->is_script_handle_unique( $this->data['handle'] ) ) {

            throw new \Exception("Assets: Handle '{$this->data['handle']}' is not unique.");

        }

        if( ! $this->javascript_exists( $this->data['src'] ) ) {

            throw new \Exception("Assets: Local stylesheet '{$this->data['src']}' does not exist.");

        }

        if( isset( $this->data['condition'] ) and ! is_string( $this->data['condition'] ) ) {

            throw new \Exception("Assets: Condition is not of type string.");

        }

        return true;

    }


    /**
     * is_script_handle_unique.
     *
     * Check if the asset handle is unique.
     *
     * @uses isset https://www.php.net/manual/en/function.isset.php
     * 
     * @param string $name asset handle.
     * 
     * @return bool
     */
    private function is_script_handle_unique( string $name ) : bool {
        
        global $wp_styles;

        return ! isset( $wp_styles->registered[ $name ] ) ? true : false;

    }


    /**
     * javascript_exists.
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
    private function javascript_exists( string $file ) : bool {

        return \filter_var( $file , FILTER_VALIDATE_URL ) || \file_exists( \get_template_directory() . $file ) ? true : false;

    }

}