<?php
/*
Plugin Name: Parent Plugin
Description: Parent Plugin for managing settings and providing API to child sites.
Version: 1.0
Author: Your Name
Text Domain: parent-plugin
*/

if ( ! defined( 'WPINC' ) ) {
    die;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-parent-plugin-activator.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-parent-plugin-deactivator.php';

register_activation_hook( __FILE__, array( 'Parent_Plugin_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Parent_Plugin_Deactivator', 'deactivate' ) );

require plugin_dir_path( __FILE__ ) . 'includes/class-parent-plugin-loader.php';
$plugin = new Parent_Plugin_Loader();
$plugin->run();
