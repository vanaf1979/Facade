<?php

namespace Facade;


use Facade\Config;
use Theme\FacadeConfig;


class ServiceBasedTheme {

    /**
     * The core config class.
     */
    private $facadeconfig = null;

    /**
     * The theme config class.
     */
    private $themeconfig = null;

    /**
     * Array of services.
     */
    private $services = null;

    /**
     * Array of services.
     */
    private $definitions = null;


    /**
     * Array of services.
     */
    private $container = null;


    /**
     * the constructor.
     */
    public function __construct() {

        $this->initialize_theme();
        
    }


    /**
     * initialize_theme.
     *
     * Call the config classes and run all services.
     *
     * @return void
     */
    public function initialize_theme() : void {

        $this->get_config_classes();

        $this->get_config_services();

        $this->get_config_definitions();

        $this->build_container();

        $this->run_theme_services();

    }


    /**
     * get_config_classes.
     *
     * Get the core and theme config classes.
     *
     * @return void
     */
    private function get_config_classes() : void {

        if( $this->facadeconfig == null ) {

            $this->facadeconfig = new Config();

        }

        if( $this->themeconfig == null ) {

            $this->themeconfig = new FacadeConfig();

        }
        
    }


    /**
     * get_config_services.
     *
     * Get the core and theme services.
     *
     * @return void
     */
    private function get_config_services() : void {

        $coreservices = $this->facadeconfig->get_service_classes();

        $themeservices = $this->themeconfig->get_service_classes();

        $this->services = array_merge( $coreservices , $themeservices ) ;

    }


    /**
     * get_config_definitions.
     *
     * Get the core and theme definitions.
     *
     * @return void
     */
    private function get_config_definitions() : void {

        $coredefinitions = $this->facadeconfig->get_definitions();

        $themedefinitions = $this->themeconfig->get_definitions();

        $this->definitions = array_merge( $coredefinitions , $themedefinitions ) ;
        
    }


    /**
     * build_container.
     *
     * ...
     *
     * @return void
     */
    private function build_container() : void {

        if( $this->container == null ) {

            $builder = new \DI\ContainerBuilder();

            $builder->addDefinitions( $this->definitions );

            $this->container = $builder->build();

        }

    }


    /**
     * run_theme_services.
     *
     * Check and instantiate the service classes.
     *
     * @return void
     */
    private function run_theme_services() : void {

        foreach ( $this->services as $id => $service ) {

            if ( is_subclass_of( $service , 'Facade\Conditional' ) ) {
                
                if( ! $service::is_needed() ) {
   
                    continue;

                }
                
            }

            $service = $this->container->get( $service  );

            if ( is_subclass_of( $service , 'Facade\Registerable' ) ) {

                $service->register();

            }

        }

    }
    
}
