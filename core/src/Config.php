<?php
/**
 * Configure Facade and the theme.
 *
 * @package    Theme
 * @subpackage Theme
 */

namespace Facade;


class Config {

    /**
     * get_service_classes.
     *
     * Store and return all services classes.
     *
     * @return array
     */
    public function get_service_classes(): array {

        return [
            // 'PublicEnqueue' => \Theme\Services\PublicEnqueue::class,
        ];

    }


    /**
     * get_definitions.
     *
     * Store and return all definitions.
     *
     * @return array
     */
    public function get_definitions(): array {

        return [
            WP_Scripts => \DI\object( function(){
                global $wp_scripts;
                return $wp_scripts;
            }),
            // FunctionsPhp\Dependencies\Theme::class => \DI\factory('FunctionsPhp\Dependencies\Theme::instance'),
        ];

    }

}
