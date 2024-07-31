<?php
/**
 * Plugin Name: Child Plugin
 * Description: A child plugin to fetch data from the parent plugin.
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: child-plugin
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'CHILD_PLUGIN_VERSION', '1.2.0' );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-child-plugin-activator.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-child-plugin-deactivator.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-child-plugin-api.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-child-plugin-admin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-child-plugin-logger.php';

register_activation_hook( __FILE__, array( 'Child_Plugin_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Child_Plugin_Deactivator', 'deactivate' ) );

function run_child_plugin() {
    $plugin = new Child_Plugin_API();
    $plugin->run();

    $admin = new Child_Plugin_Admin();
    $admin->run();
}

run_child_plugin();
