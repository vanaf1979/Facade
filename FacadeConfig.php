<?php
/**
 * Configure Facade and the theme.
 *
 * @package    Theme
 * @subpackage Theme
 */

namespace Theme;


class FacadeConfig {

    /**
     * the constructor.
     */
    public function __construct() { }


    /**
     * get_service_classes.
     *
     * Store and return all services classes.
     *
     * @return array
     */
    public function get_service_classes(): array {

        return [
            'PublicEnqueue' => \Theme\Services\PublicEnqueue::class,
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
            //FunctionsPhp\Dependencies\Theme::class => \DI\factory('FunctionsPhp\Dependencies\Theme::instance'),
            //FunctionsPhp\Dependencies\Single::class => \DI\factory('FunctionsPhp\Dependencies\Single::instance'),
        ];

    }

}
