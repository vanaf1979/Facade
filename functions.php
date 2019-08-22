<?php
/*!
Theme Name:         Facade
Theme URI:          https://vanaf1979.nl/
Description:        Test theme for the Facade framework
Version:            1.0.0
Author:             Vanaf1979
Author URI:         https://vanaf1979.nl
Text Domain:        facade
License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/

namespace Facade;


if ( ! defined( 'ABSPATH' ) ) {
    
    die();
    
}

require_once __DIR__ . '/vendor/autoload.php';

/* Run the theme */
( new \Facade\ServiceBasedTheme() );
?>
